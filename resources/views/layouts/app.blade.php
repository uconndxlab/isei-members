<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ISEI Member Directory')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/htmx.org@2.0.2" integrity="sha384-Y7hw+L/jvKeWIRRkqWYfPcvVxHzVzn5REgzbawhxAuQGwX1XWe70vji+VSeHOThJ" crossorigin="anonymous"></script>

</head>
<body>
    {{-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">ISEI Member Directory</a>
        </div>
    </nav> --}}

    <!-- admin dash if user is admin -->
    @if (optional(auth()->user())->is_admin)
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <!-- logout, create member -->
                <a class="navbar-brand" href="{{ route('admin.index') }}">ISEI Member Directory</a>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.members.create') }}">Create Member</a>
                    </li>

                    <li class="nav-item">
                        <!-- export members -->
                        <a class="nav-link" href="{{ route('admin.members.export') }}">Export Members</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                    </li>
                </ul>

            </div>
        </nav>
    @endif

    <!-- messages area -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    @if($isIframe)
    <script>
        // Send height updates to parent window
        function sendHeightToParent() {
            const height = document.documentElement.scrollHeight;
            window.parent.postMessage({
                type: 'iframe-height',
                height: height
            }, '*');
        }

        window.addEventListener('load', sendHeightToParent);
        window.addEventListener('resize', sendHeightToParent);
        
        const observer = new MutationObserver(sendHeightToParent);
        observer.observe(document.body, { childList: true, subtree: true, attributes: true });
        
        document.body.addEventListener('htmx:afterSwap', sendHeightToParent);
        document.body.addEventListener('htmx:afterSettle', sendHeightToParent);
        
        sendHeightToParent();
    </script>
    @endif
</body>
</html>
