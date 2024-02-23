<footer class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-2">
                <svg width="24" height="24" viewBox="0 0 150 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="mb-2">
                    <path d="M127.499 104.831H146.249C148.321 104.831 149.999 106.51 149.999 108.581V116.081C149.999 118.153 148.321 119.831 146.249 119.831H112.499V29.8319H89.9994V14.832H116.249C122.453 14.832 127.499 20.0328 127.499 26.4218V104.831Z" fill="white"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M28.1811 11.8952L73.1808 0.237466C77.9151 -0.990652 82.4995 2.71948 82.4995 7.77726V120H3.74998C1.67811 120 0 118.322 0 116.25V108.75C0 106.678 1.67811 105 3.74998 105H22.4999V19.435C22.4999 15.8678 24.8412 12.76 28.1811 11.8952ZM56.2496 60.0004C56.2496 64.1418 58.7692 67.5003 61.8746 67.5003C64.9801 67.5003 67.4996 64.1418 67.4996 60.0004C67.4996 55.859 64.9801 52.5004 61.8746 52.5004C58.7692 52.5004 56.2496 55.859 56.2496 60.0004Z" fill="#EF6461"/>
                </svg>
                <small class="d-block mb-3 text-muted">©TPV Dell office 2024</small>
            </div>
            <div class="col-6 col-md-2">
                <h5>RoomBooker</h5>
                <ul class="list-unstyled text-small">
                    <li><a class="text-muted" href="{{ route('home') }}">Home</a></li>
                    <li><a class="text-muted" href="{{ route('about') }}">About</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-2">
                <h5>Support</h5>
                <ul class="list-unstyled text-small">
                    <li><a class="text-muted" href="{{ route('faq') }}">FAQ</a></li>
                    <li><a class="text-muted" href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-2">
                <h5>Account</h5>
                <ul class="list-unstyled text-small">
                    <li><a class="text-muted" href="{{ route('register') }}">Sign up</a></li>
                    <li><a class="text-muted" href="{{ route('login') }}">Log in</a></li>
                    @auth
                    <li><a class="text-muted" href="{{ route('dashboard.index') }}">Go to dashboard</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>
</footer>
