<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Student Exam Dashboard')</title>

  {{-- Tailwind & Icons via CDN (tanpa Vite) --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  <script>
    tailwind.config = { darkMode: 'class' };
  </script>

  <style>
    :root{
      --background: oklch(0.145 0 0);
      --foreground: oklch(0.985 0 0);
      --card: oklch(0.205 0 0);
      --card-foreground: oklch(0.985 0 0);
      --primary: oklch(0.488 0.243 264.376);
      --primary-foreground: oklch(0.985 0 0);
      --secondary: oklch(0.269 0 0);
      --secondary-foreground: oklch(0.985 0 0);
      --muted: oklch(0.269 0 0);
      --muted-foreground: oklch(0.708 0 0);
      --border: oklch(0.269 0 0);
      --sidebar: oklch(0.205 0 0);
      --sidebar-foreground: oklch(0.985 0 0);
    }
    body{ background-color: var(--background); color: var(--foreground); }
    .bg-background{ background-color: var(--background); }
    .bg-card{ background-color: var(--card); }
    .bg-primary{ background-color: var(--primary); }
    .bg-secondary{ background-color: var(--secondary); }
    .bg-muted{ background-color: var(--muted); }
    .bg-sidebar{ background-color: var(--sidebar); }
    .text-foreground{ color: var(--foreground); }
    .text-muted-foreground{ color: var(--muted-foreground); }
    .text-primary-foreground{ color: var(--primary-foreground); }
    .border-border{ border-color: var(--border); }
    .nav-active{ background-color: var(--primary); color: var(--primary-foreground); }
  </style>

  @stack('head')
</head>
<body class="bg-background text-foreground">
  <div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-sidebar border-r border-border flex flex-col">
      <!-- Logo -->
      <div class="p-6 border-b border-border">
        <div class="flex items-center space-x-3">
          <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
            <i class="fas fa-graduation-cap text-primary-foreground"></i>
          </div>
          <div>
            <h1 class="text-lg font-bold">Computer Based Test</h1>
            <p class="text-sm text-muted-foreground">SMK Negeri 2 Balige</p>
          </div>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 p-4">
        <ul class="space-y-2">
          <li>
            <a href="{{ route('Siswa.Course.index') }}"
               class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-secondary transition-colors {{ request()->routeIs('dashboard.courses') ? 'nav-active' : '' }}">
              <i class="fas fa-book-open w-5 h-5"></i>
              <span>My Courses</span>
            </a>
          </li>
          <li>
<<<<<<< Updated upstream
<<<<<<< Updated upstream
            <a href="#"
               class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-secondary transition-colors {{ request()->routeIs('dashboard.practice') ? 'nav-active' : '' }}">
              <i class="fas fa-dumbbell w-5 h-5"></i>
              <span>Practice Questions</span>
            </a>
          </li>
          <li>
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
            <a href=""
               class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-secondary transition-colors {{ request()->routeIs('dashboard.grades') ? 'nav-active' : '' }}">
              <i class="fas fa-chart-bar w-5 h-5"></i>
              <span>Grades</span>
            </a>
          </li>
          <li>
            <a href="{{ route('Siswa.enroll.kurikulum') }}"
               class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-secondary transition-colors {{ request()->routeIs('dashboard.category') ? 'nav-active' : '' }}">
              <i class="fas fa-th-large w-5 h-5"></i>
              <span>Course Category</span>
            </a>
          </li>
        </ul>
      </nav>
      <!-- Logout -->
      <div class="p-4 border-t border-border">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit"
                  class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-red-600 hover:text-white transition-colors">
            <i class="fas fa-sign-out-alt w-5 h-5"></i>
            <span>Logout</span>
          </button>
        </form>
      </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">
      <!-- Header -->
      <header class="bg-card border-b border-border p-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div>
              {{-- Ganti ke data user dinamis sesuai kebutuhan --}}
            <h2 class="text-xl font-semibold">
            {{ \App\Models\Siswa::where('id_user', auth()->id())->value('nama_siswa')
                ?? (auth()->user()->name ?? auth()->user()->email ?? 'Siswa') }}
            </h2>
              <p class="text-muted-foreground">Student Dashboard</p>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <button class="p-2 hover:bg-secondary rounded-lg"><i class="fas fa-bell"></i></button>
            <button class="p-2 hover:bg-secondary rounded-lg"><i class="fas fa-cog"></i></button>
          </div>
        </div>
      </header>

      <!-- Main Content -->
      <main class="flex-1 overflow-auto p-6">
        <!-- Breadcrumb -->
        <nav class="mb-6">
          <ol class="flex items-center space-x-2 text-sm text-muted-foreground">
            <li><a href="" class="hover:text-foreground">Dashboard</a></li>
            @hasSection('breadcrumb')
              <li><span class="mx-2">/</span></li>
              @yield('breadcrumb')
            @endif
          </ol>
        </nav>

        @yield('content')
      </main>
    </div>
  </div>

  @stack('scripts')
</body>
</html>
