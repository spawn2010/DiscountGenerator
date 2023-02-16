<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PHPUnit\Exception;

class DiscountController extends Controller
{
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
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

        if (session()->has('discount')) {
            $discount = Discount::where('code', session('discount'))->first();
            if (($currentDate- strtotime($discount->created_at)) > 3600){
                Discount::create([
                    'value' => $value,
                    'code' => $code,
                    'user_id' => $userId,
                ]);
                session(['discount' => $code]);
            }else{
                return ['value' => $discount->value, 'code' => $discount->code];
            }
        }else{
            Discount::create([
                'value' => $value,
                'code' => $code,
                'user_id' => $userId,
            ]);
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
