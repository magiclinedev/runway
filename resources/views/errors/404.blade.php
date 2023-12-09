<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>

  <body>

    <div class="min-h-full w-full flex flex-col items-center justify-center overflow-hidden mt-10">

        <div class="container flex flex-col md:flex-row items-center justify-center px-5 text-gray-700 mt-8">
            <div class="w-100">
                <div class="text-5xl font-dark font-bold">
                    <h1 class="relative text-9xl tracking-tighter-less text-shadow font-sans font-bold">
                        <span>4</span><span>0</span><span>4</span></h1>
                </div>
                <p class="text-4xl font-bold text-gray-700 capitalize tracking-wide mt-8">Page not found</p>
                <p class="text-xl text-gray-700 mt-4">we are sorry, but the page you requested was not found</p>

                <a href="{{ route('home') }}">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 mt-2 rounded transition-all">
                        Go Back to Home Page <i class="fa fa-download ml-2"></i>
                    </button>
                </a>
            </div>
        </div>
    </div>

    <style>
        .error-bg {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1600 900'%3E%3Cpolygon fill='%23f0b608' points='957 450 539 900 1396 900'/%3E%3Cpolygon fill='%23e6d710' points='957 450 872.9 900 1396 900'/%3E%3Cpolygon fill='%23e7af05' points='-60 900 398 662 816 900'/%3E%3Cpolygon fill='%23e7d808' points='337 900 398 662 816 900'/%3E%3Cpolygon fill='%23d8a408' points='1203 546 1552 900 876 900'/%3E%3Cpolygon fill='%23f1e213' points='1203 546 1552 900 1162 900'/%3E%3Cpolygon fill='%23f0b607' points='641 695 886 900 367 900'/%3E%3Cpolygon fill='%23e4d506' points='587 900 641 695 886 900'/%3E%3Cpolygon fill='%23eab822' points='1710 900 1401 632 1096 900'/%3E%3Cpolygon fill='%23e8da14' points='1710 900 1401 632 1365 900'/%3E%3Cpolygon fill='%23e8b008' points='1210 900 971 687 725 900'/%3E%3Cpolygon fill='%23edde14' points='943 900 1210 900 971 687'/%3E%3C/svg%3E");
        }
        .tracking-tighter-less {
            letter-spacing: -0.75rem;
        }
        .text-shadow {
            text-shadow: -8px 0 0 rgb(102 123 242);
        }
    </style>

  </body>

</html>
