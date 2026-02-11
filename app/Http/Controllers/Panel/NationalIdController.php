<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NationalIdController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        $data = [
            'pageTitle' => trans('auth.national_id_required_title'),
            'user' => $user,
        ];

        return view(getTemplate() . '.panel.national_id', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'national_id' => ['required', 'string', 'regex:/^[124]\d{9}$/'],
        ], [
            'national_id.regex' => trans('auth.national_id_invalid_format'),
        ]);

        $user = auth()->user();
        $user->update([
            'national_id' => $request->input('national_id'),
        ]);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('auth.national_id_saved_successfully'),
            'status' => 'success',
        ];

        return redirect('/panel')->with(['toast' => $toastData]);
    }
}
