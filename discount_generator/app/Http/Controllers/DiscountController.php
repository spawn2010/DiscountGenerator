<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PHPUnit\Exception;

class DiscountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        return view('discount');
    }

    /**
     * @throws \Exception
     */
    public function createDiscount(Request $request): array
    {
        $code = (uniqid('SALE', false));
        $value = random_int(1,50);
        $currentDate = strtotime(date('Y-m-d H:m:s'));
        $userId = auth()->id();

        $this->validate($request, [
            'code' => ['max:255', 'unique'],
            'value' => ['integer', 'min:1', 'max:50'],
            'user_id' => ['integer', 'exists:users'],
        ]);

        $create = static function ($code, $value, $userId){
            Discount::create([
                'value' => $value,
                'code' => $code,
                'user_id' => $userId,
            ]);
        };

        if (session()->has('discount')) {
            $discount = Discount::where('code', session('discount'))->first();
            if (($currentDate- strtotime($discount->created_at)) > 3600){
                $create($code,$value,$userId);
                session(['discount' => $code]);
            }else{
                return ['value' => $discount->value, 'code' => $discount->code];
            }
        }else{
            $create($code,$value,$userId);
            session(['discount' => $code]);
        }

        return ['value' => $value, 'code' => $code];
    }

    /**
     * @throws ValidationException
     */
    public function checkDiscount(Request $request): bool
    {
        $code = $request->code;
        $this->validate($request, [
            'code' => ['string', 'max:255'],
        ]);
        $discount = Discount::where('code', $code)->first();
        $currentDate = strtotime(date('Y-m-d H:m:s'));

        return ($discount && $discount->user_id === auth()->id() && ($currentDate - strtotime($discount->created_at)) < 10800);

    }
}
