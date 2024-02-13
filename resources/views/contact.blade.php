@extends('layouts.front.app')

@section('content')
<section class="contact-section">
    <div class="row">
        <header class="contact-content">
            <h1>{{ __('Need help?') }}</h1>
            <p>Email us directly to <a href="mailto:support@roombooker.com">support@roombooker.com</a>, or fill out the contact form</p>
        </header>
        <div class="contact-card">
            <div id="contact-body" class="card-body">
                <form method="POST" id="contact">
                    <div class="form-group">
                        <label for="title">{{ __('Title') }} <span class="text-danger">*</span></label>
                        <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                            name="title" value="{{ old('title') }}" placeholder="Your message title" required
                            autofocus>

                        @if ($errors->has('title'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">{{ __('E-mail Address') }} <span class="text-danger">*</span></label>
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                name="email" value="{{ old('email') }}" placeholder="ex. johndoe@mail.com" required
                                autofocus>

                            @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="form-group col-md-6">
                            <label for="name">{{ __('Your name') }}</label>
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                name="name" value="{{ old('name') }}" placeholder="ex. John Doe" required
                                autofocus>

                            @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="content">{{ __('Message') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" name="content" id="content" rows="10" required autofocus>
                            {{ old('content') }}
                        </textarea>

                        @if ($errors->has('content'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('content') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="verification">{{ __('Verification') }} <span class="text-danger">*</span></label>
                        <input id="verification" type="number" class="form-control"
                            name="verification" placeholder="Enter the result here" required
                            autofocus aria-describedby="verificationHelpId">
                        <small id="verificationHelpId">What is the result of <span id="aaa" class="text-muted font-weight-bold"></span> ?</small>
                    </div>

                    <div class="form-group">
                        <button id="submit" type="submit" class="btn btn-primary" disabled>
                            {{ __('Submit') }}
                        </button>
                        <span id="wait" class="d-none ml-2">Sending <i class="spinner fas fa-circle-notch text-muted"></i></span>
                        <small id="error" class="text-danger font-weight-bold ml-1 d-none">Invalid submission</small>
                        <div id="success" class="alert alert-success" style="display: none" role="alert">
                            Your message was sent successfully
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom-script')
<script>
    $(document).ready(function() {
        var a = Math.ceil(Math.random()*20);
        var b = Math.ceil(Math.random()*10);
        var ops = "*+-";
        var op =  ops[Math.floor(Math.random()*3)];
        let res = eval(a + op + b);
        $('#aaa').text(`${a} ${op} ${b}`);
        $('#verification').keyup(function(e) {
            if($(this).val() == res) {
                $('#submit').attr('disabled', false);
            } else {
                $('#submit').attr('disabled', 'disabled');
            }
        });
        $('#contact').submit(e => e.preventDefault());
        $('#submit').click(function(e) {
            var data = $('#contact').serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});
            data._token = $('meta[name=csrf-token]').attr('content');
            $.ajax({
                method: 'post',
                url: '/api/contact',
                data: $.param(data),
                beforeSend: function (xhr) {
                    $('#wait').removeClass('d-none');
                    $('#error').addClass('d-none');
                    $('#contact input, #contact textarea').attr('disabled', 'disabled');
                },
                success: function(data) {
                    $('#wait').addClass('d-none');
                    $('#success').slideDown();
                    $('#submit').slideUp();
                },
                error: function(xhr, message, error) {
                    console.log(xhr);
                    $('#contact input, #contact textarea').attr('disabled', false);
                    $('#wait').addClass('d-none');
                    $('#error').removeClass('d-none');
                    $('#error').text('Invalid form submission');
                },
            });
        });
    });
</script>
@endsection
