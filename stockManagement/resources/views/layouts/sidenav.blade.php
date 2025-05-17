<div class="sidenav bg-gray-900 min-h-screen w-56 flex flex-col py-6">
              <div class="text-2xl font-bold text-white px-6 mb-8   border-b">
                  Stock Management
              </div>
              <nav class="flex flex-col gap-4 px-2">
                            <a href="/dashboard"
                            class="sidenav-link{{ Request::is('dashboard') ? ' active' : '' }}">
                             Dashboard
                         </a>
                         <a href="/stockin"
                            class="sidenav-link{{ Request::is('stockin') ? ' active' : '' }}">
                             Stock In
                         </a>
                         <a href="/products"
                            class="sidenav-link{{ Request::is('products') ? ' active' : '' }}">
                             Products
                         </a>
                         <a href="/stockout"
                            class="sidenav-link{{ Request::is('stockout') ? ' active' : '' }}">
                             Stock Out
                         </a>
                         <a href="/reports"
                            class="sidenav-link{{ Request::is('reports') ? ' active' : '' }}">
                             Reports
                         </a>
                  <form method="POST" action="/logout">
                      @csrf
                      <button type="submit" class="sidenav-link w-full text-left">Logout</button>
                  </form>
              </nav>
          </div>