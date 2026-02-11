@extends(getTemplate().'.layouts.app')

@section('content')
    <div class="container" style="max-width: 600px; margin-top: 60px;">
        <div class="card shadow-sm">
            <div class="card-body p-30">
                <div class="text-center mb-20">
                    <i class="fa fa-id-card text-primary" style="font-size: 48px;"></i>
                </div>

                <h2 class="font-20 font-weight-bold text-center mb-10">{{ trans('auth.national_id_required_title') }}</h2>
                <p class="text-center text-muted mb-25">{{ trans('auth.national_id_required_description') }}</p>

                <form method="POST" action="/panel/national-id/store">
                    @csrf

                    <div class="form-group">
                        <label class="input-label" for="national_id">{{ trans('auth.national_id') }}:</label>
                        <input name="national_id"
                               type="text"
                               id="national_id"
                               value="{{ old('national_id') }}"
                               class="form-control @error('national_id') is-invalid @enderror"
                               placeholder="{{ trans('auth.national_id_placeholder') }}"
                               maxlength="10"
                               pattern="[124]\d{9}"
                               required
                               dir="ltr">
                        @error('national_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                        <small class="form-text text-muted">{{ trans('auth.national_id_hint') }}</small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mt-20">{{ trans('auth.save_national_id') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
