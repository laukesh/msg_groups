@extends('layouts.auth')

@section('title', 'Register User')

@section('content')

<div class="container-fluid py-4">

    <div class="row justify-content-center">

        <div class="col-md-8 col-lg-7">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person-plus me-2"></i>
                        Register User
                    </h5>
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('register') }}">

                        @csrf

                        {{-- Name --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Name
                            </label>

                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Enter full name"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Email --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="Enter email address"
                                   required>

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    {{-- =========================================================
     PASSWORD
========================================================= --}}
<div class="mb-3">

    <label for="password" class="form-label">
        Password
    </label>

    <div class="input-group">

        <input type="password"
               id="password"
               name="password"
               value="{{ old('password') }}"
               class="form-control @error('password') is-invalid @enderror"
               placeholder="Enter password"
               autocomplete="new-password"
               required>

        <button type="button"
                class="btn btn-outline-secondary toggle-password"
                data-target="password"
                aria-label="Show password">

          <i class="fas fa-eye"></i>

        </button>

        <button type="button"
                id="generatePassword"
                class="btn btn-outline-primary">

            <i class="bi bi-shield-lock me-1"></i>
            Generate

        </button>

    </div>

    {{-- Laravel Password Error --}}
    @error('password')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror

    {{-- Password Strength --}}
    <div id="passwordStrengthContainer" class="mt-2">

        <div class="progress"
             style="height: 6px;">

            <div id="passwordStrengthBar"
                 class="progress-bar"
                 style="width: 0%;">
            </div>

        </div>

        <small id="passwordStrengthText"
               class="text-muted">
            Enter a strong password
        </small>

    </div>

    {{-- Password Requirements --}}
    <div id="passwordRequirements"
         class="mt-2 small">

        <div id="lengthCheck" class="text-muted">
            <i class="bi bi-circle me-1"></i>
            At least 8 characters
        </div>

        <div id="uppercaseCheck" class="text-muted">
            <i class="bi bi-circle me-1"></i>
            One uppercase letter
        </div>

        <div id="lowercaseCheck" class="text-muted">
            <i class="bi bi-circle me-1"></i>
            One lowercase letter
        </div>

        <div id="numberCheck" class="text-muted">
            <i class="bi bi-circle me-1"></i>
            One number
        </div>

        <div id="specialCheck" class="text-muted">
            <i class="bi bi-circle me-1"></i>
            One special character
        </div>

    </div>

</div>


{{-- =========================================================
     CONFIRM PASSWORD
========================================================= --}}
<div class="mb-3">

    <label for="password_confirmation"
           class="form-label">

        Confirm Password

    </label>

    <div class="input-group">

        <input type="password"
               id="password_confirmation"
               name="password_confirmation"
               class="form-control"
               placeholder="Confirm password"
               autocomplete="new-password"
               required>

        <button type="button"
                class="btn btn-outline-secondary toggle-password"
                data-target="password_confirmation"
                aria-label="Show password">

           <i class="fas fa-eye"></i>
        </button>

    </div>

    {{-- Password Match Message --}}
    <small id="passwordMatch"
           class="d-block mt-1">
    </small>

</div>

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-check-circle me-1"></i>
                                Register User

                            </button>

                            <a href="{{ route('admin.users.index') }}"
                               class="btn btn-secondary">

                                Cancel

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.querySelector('form');

    const password = document.getElementById('password');
    const confirmation =
        document.getElementById('password_confirmation');

    const strengthBar =
        document.getElementById('passwordStrengthBar');

    const strengthText =
        document.getElementById('passwordStrengthText');

    const passwordMatch =
        document.getElementById('passwordMatch');

    const generateButton =
        document.getElementById('generatePassword');


    /* =========================================================
       SHOW / HIDE PASSWORD
    ========================================================= */

    document.querySelectorAll('.toggle-password').forEach(function (button) {

        button.addEventListener('click', function () {

            const target =
                document.getElementById(
                    this.dataset.target
                );

            const icon =
                this.querySelector('i');

            if (target.type === 'password') {

                target.type = 'text';

                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');

                this.setAttribute(
                    'aria-label',
                    'Hide password'
                );

            } else {

                target.type = 'password';

                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');

                this.setAttribute(
                    'aria-label',
                    'Show password'
                );

            }

        });

    });


    /* =========================================================
       PASSWORD STRENGTH
    ========================================================= */

    function checkPasswordStrength(value) {

        let score = 0;

        const checks = {

            length:
                value.length >= 8,

            uppercase:
                /[A-Z]/.test(value),

            lowercase:
                /[a-z]/.test(value),

            number:
                /[0-9]/.test(value),

            special:
                /[^A-Za-z0-9]/.test(value)

        };


        /* Count score */

        Object.values(checks).forEach(function (valid) {

            if (valid) {
                score++;
            }

        });


        /* Update requirement UI */

        updateRequirement(
            'lengthCheck',
            checks.length
        );

        updateRequirement(
            'uppercaseCheck',
            checks.uppercase
        );

        updateRequirement(
            'lowercaseCheck',
            checks.lowercase
        );

        updateRequirement(
            'numberCheck',
            checks.number
        );

        updateRequirement(
            'specialCheck',
            checks.special
        );


        /* Reset bar */

        strengthBar.className =
            'progress-bar';

        if (value.length === 0) {

            strengthBar.style.width = '0%';

            strengthText.textContent =
                'Enter a strong password';

            strengthText.className =
                'text-muted';

            return;

        }


        /* Strength */

        const percentage = score * 20;

        strengthBar.style.width =
            percentage + '%';


        if (score <= 2) {

            strengthBar.classList.add(
                'bg-danger'
            );

            strengthText.textContent =
                'Weak password';

            strengthText.className =
                'text-danger';

        }

        else if (score <= 4) {

            strengthBar.classList.add(
                'bg-warning'
            );

            strengthText.textContent =
                'Medium password';

            strengthText.className =
                'text-warning';

        }

        else {

            strengthBar.classList.add(
                'bg-success'
            );

            strengthText.textContent =
                'Strong password';

            strengthText.className =
                'text-success';

        }

    }


    /* =========================================================
       REQUIREMENT UI
    ========================================================= */

    function updateRequirement(id, valid) {

        const element =
            document.getElementById(id);

        const icon =
            element.querySelector('i');

        if (valid) {

            element.classList.remove(
                'text-muted'
            );

            element.classList.add(
                'text-success'
            );

            icon.classList.remove(
                'bi-circle'
            );

            icon.classList.add(
                'bi-check-circle-fill'
            );

        } else {

            element.classList.remove(
                'text-success'
            );

            element.classList.add(
                'text-muted'
            );

            icon.classList.remove(
                'bi-check-circle-fill'
            );

            icon.classList.add(
                'bi-circle'
            );

        }

    }


    /* =========================================================
       PASSWORD MATCH
    ========================================================= */

    function checkPasswordMatch() {

        const passwordValue =
            password.value;

        const confirmationValue =
            confirmation.value;


        /* Don't show anything initially */

        if (!confirmationValue) {

            passwordMatch.textContent = '';

            passwordMatch.className =
                'd-block mt-1';

            return true;
        }


        if (passwordValue === confirmationValue) {

            passwordMatch.innerHTML =
                '<i class="bi bi-check-circle-fill me-1"></i>' +
                'Passwords match';

            passwordMatch.className =
                'text-success d-block mt-1';

            return true;

        }


        passwordMatch.innerHTML =
            '<i class="bi bi-x-circle-fill me-1"></i>' +
            'Passwords do not match';

        passwordMatch.className =
            'text-danger d-block mt-1';

        return false;

    }


    /* =========================================================
       GENERATE STRONG PASSWORD
    ========================================================= */

    function generateStrongPassword(length = 16) {

        const uppercase =
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        const lowercase =
            'abcdefghijklmnopqrstuvwxyz';

        const numbers =
            '0123456789';

        const special =
            '!@#$%^&*()-_=+';


        const all =
            uppercase +
            lowercase +
            numbers +
            special;


        let password = '';


        /* Guarantee required characters */

        password += randomCharacter(
            uppercase
        );

        password += randomCharacter(
            lowercase
        );

        password += randomCharacter(
            numbers
        );

        password += randomCharacter(
            special
        );


        /* Remaining characters */

        while (password.length < length) {

            password += randomCharacter(all);

        }


        /* Secure-ish shuffle */

        password =
            password
                .split('')
                .sort(function () {
                    return Math.random() - 0.5;
                })
                .join('');


        return password;

    }


    function randomCharacter(characters) {

        return characters.charAt(
            Math.floor(
                Math.random() *
                characters.length
            )
        );

    }


    /* =========================================================
       GENERATE PASSWORD BUTTON
    ========================================================= */

    generateButton.addEventListener(
        'click',
        function () {

            const generated =
                generateStrongPassword(16);


            password.value =
                generated;

            confirmation.value =
                generated;


            /* Show generated password */

            password.type = 'text';

            confirmation.type = 'text';


            /* Update icons */

            document
                .querySelectorAll('.toggle-password i')
                .forEach(function (icon) {

                    icon.classList.remove(
                        'bi-eye'
                    );

                    icon.classList.add(
                        'bi-eye-slash'
                    );

                });


            /* Update validation */

            checkPasswordStrength(
                generated
            );

            checkPasswordMatch();

        }
    );


    /* =========================================================
       PASSWORD INPUT
    ========================================================= */

    password.addEventListener(
        'input',
        function () {

            checkPasswordStrength(
                this.value
            );

            checkPasswordMatch();

        }
    );


    /* =========================================================
       CONFIRM PASSWORD INPUT
    ========================================================= */

    confirmation.addEventListener(
        'input',
        function () {

            checkPasswordMatch();

        }
    );


    /* =========================================================
       FORM SUBMIT VALIDATION
    ========================================================= */

    form.addEventListener(
        'submit',
        function (event) {

            const passwordValue =
                password.value;

            const confirmationValue =
                confirmation.value;


            /* Password mismatch */

            if (
                passwordValue !==
                confirmationValue
            ) {

                event.preventDefault();

                checkPasswordMatch();

                confirmation.focus();

                return false;

            }


            /* Minimum password validation */

            if (passwordValue.length < 8) {

                event.preventDefault();

                checkPasswordStrength(
                    passwordValue
                );

                password.focus();

                return false;

            }

        }
    );


    /* =========================================================
       INITIAL STATE
    ========================================================= */

    if (password.value) {

        checkPasswordStrength(
            password.value
        );

    }

});
</script>
@endsection