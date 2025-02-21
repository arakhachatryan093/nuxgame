<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;

class MainController extends Controller
{
    /**
     * @param Request $request
     * @return Redirector|RedirectResponse
     */
    public function register(Request $request): Redirector|RedirectResponse
    {
        $request->validate([
            'username' => 'required|string',
            'phone' => 'required|string',
        ]);

        $special_page_link = Str::uuid()->toString();
        User::query()->updateOrCreate(['username' => $request->get('username'), 'phone' => $request->get('phone')], [
            'special_page_link' => $special_page_link,
            'special_page_link_expires_at' => Carbon::now()->addDays(7),
        ]);

        return redirect(route('special_page.index', ['link' => $special_page_link]));
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function specialPage(Request $request, string $link): Response
    {
        $user = User::query()->where('special_page_link', $link)->firstOrFail();
        return response()->view('special_page', compact('user'));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function regenerate(Request $request, User $user): JsonResponse
    {
        try {
            $user->update([
                'special_page_link' => Str::uuid()->toString(),
                'special_page_link_expires_at' => Carbon::now()->addDays(7),
            ]);
            $user->fresh();
            return \response()->json(['user' => $user]);
        } catch (\Exception $exception) {
            return \response()->json(['user' => null], 500);
        }
    }

    /**
     * @param Request $request
     * @param User $user
     * @return bool
     */
    public function deleteLink(Request $request, User $user): bool
    {
        return $user->update([
            'special_page_link' => null,
            'special_page_link_expires_at' => null,
        ]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function imFeelingLucky(Request $request, User $user): JsonResponse
    {
        $randomNumber = rand(1, 1000);
        $is_win = $randomNumber % 2 === 0;
        $winAmount = $is_win ? $this->calculateWinAmount($randomNumber) : 0;

        $user->history()->create([
            'random_number' => $randomNumber,
            'is_win' => $is_win,
            'win_amount' => $winAmount,
        ]);

        return \response()->json(['user' => $user, 'random_number' => $randomNumber, 'win_amount' => $winAmount, 'is_win' => $is_win]);
    }

    /**
     * @param float $number
     * @return float|int
     */
    private function calculateWinAmount(float $number): float|int
    {
        if ($number > 900) return $number * 0.7;
        if ($number > 600) return $number * 0.5;
        if ($number > 300) return $number * 0.3;
        return $number * 0.1;
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function getHistory(Request $request, User $user): JsonResponse
    {
        $user->load('latestHistory');
        return \response()->json(['user' => $user]);
    }
}
