<div class="header-bar flex items-center justify-between bg-white px-6 py-4 shadow">
              <span class="text-xl font-bold text-gray-900">@yield('page_title', 'Dashboard')</span>
              <span class="user-initial flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-lg font-bold">
                  {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
              </span>
          </div>