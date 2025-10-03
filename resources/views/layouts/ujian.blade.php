<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Exam - Student Dashboard')</title>

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
      --primary: oklch(0.488 0.243 264.376);
      --secondary: oklch(0.269 0 0);
      --muted: oklch(0.269 0 0);
      --border: oklch(0.269 0 0);
    }
    body{ background-color: var(--background); color: var(--foreground); }
    .bg-background{ background-color: var(--background); }
    .bg-card{ background-color: var(--card); }
    .bg-primary{ background-color: var(--primary); }
    .bg-secondary{ background-color: var(--secondary); }
    .bg-muted{ background-color: var(--muted); }
    .text-foreground{ color: var(--foreground); }
    .text-muted-foreground{ color: var(--muted); }
    .border-border{ border-color: var(--border); }
  </style>

  @stack('head')
</head>
<body class="bg-background text-foreground">
  <div class="flex h-screen flex-col">
    <!-- Header -->
    <header class="bg-card border-b border-border p-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <div>
            <h2 class="text-xl font-semibold">
            {{ \App\Models\Siswa::where('id_user', auth()->id())->value('nama_siswa')
                ?? (auth()->user()->name ?? auth()->user()->email ?? 'Siswa') }}
            </h2>
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
      @yield('content')
    </main>
  </div>

  @stack('scripts')
</body>
</html>
