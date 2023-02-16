<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        $currentDate = date('Y-m-d H:m:s');

        Discount::create([
            'value' => $value,
            'code' => $code,
            'user_id' => auth()->id(),
        ]);
        return ['value' => $value, 'code' => $code];
    }

    public function checkDiscount(Request $request)
    {
        return !is_null(Discount::where('code', $request->code)->where('user_id', auth()->id())->first());
    }

    /**
     * @throws \Exception
     */
    public function test(Request $request)
    {
//
    }
}
