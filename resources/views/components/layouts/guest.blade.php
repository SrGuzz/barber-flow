{{-- resources/views/components/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>

    {{-- Plugins e Estilos --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/pt.js"></script>
    <script src="//unpkg.com/@alpinejs/mask@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/tsparticles@2/tsparticles.bundle.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen font-sans antialiased bg-gray-300 relative" data-theme="light">   
    
    {{-- Conteúdo da página --}}
    <main class="min-h-screen relative overflow-auto flex items-center justify-center">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.9.0/p5.min.js"></script>
        <script>
          let particles = [];
        
          function setup() {
            createCanvas(windowWidth, windowHeight);
            rectMode(CENTER);
        
            // Cria 60 partículas
            for (let i = 0; i < 60; i++) {
              particles.push(new Particle());
            }
          }
        
          function draw() {
            background(250);
            for (let p of particles) {
              p.update();
              p.show();
            }
          }
        
          class Particle {
            constructor() {
              // Define a posição inicial da partícula
              this.initialX = random(width);
              this.initialY = random(height);
              this.size = random(15, 25);
              
              // Para rotação opcional dos elementos
              this.angle = random(TWO_PI);
              this.rotationSpeed = random(-0.02, 0.02);
              
              // Parâmetro para o movimento no padrão "infinito"
              this.t = random(TWO_PI);
              this.speed = random(0.003, 0.003);
              
              // Amplitudes para definir o quanto a partícula se move em cada direção
              // Para um desenho mais horizontal, a amplitude em X costuma ser maior que em Y
              this.amplitudeX = random(40, 80);
              this.amplitudeY = random(20, 40);
              
              let colors = [
                color(80, 102, 235),
                color(219, 165, 60),
                color(142, 119, 210),
                color(44, 62, 125),
              ];
              this.color = random(colors);
            }
        
            update() {
              // Incrementa o parâmetro t
              this.t += this.speed;
              
              // Equações paramétricas para o padrão "infinito"
              // x varia como A * sin(t) e y como B * sin(2t),
              // fazendo com que a partícula descreva um caminho semelhante a um 8 horizontal.
              let offsetX = this.amplitudeX * sin(this.t);
              let offsetY = this.amplitudeY * sin(2 * this.t);
              
              // Define a posição atual com base na posição inicial + deslocamento
              this.x = this.initialX + offsetX;
              this.y = this.initialY + offsetY;
              
              // Atualiza o ângulo para a rotação do retângulo, se desejado
              this.angle += this.rotationSpeed;
            }
        
            show() {
              push();
              translate(this.x, this.y);
              rotate(this.angle);
              noStroke();
              fill(this.color);
              rect(0, 0, this.size, this.size, this.size * 0.3);
              pop();
            }
          }
        
          function windowResized() {
            resizeCanvas(windowWidth, windowHeight);
          }
        </script>
        <div class="absolute w-full px-4 sm:max-w-md ">
            {{ $slot }}
        </div>
    </main>

    {{-- TOAST / scripts --}}
    <x-toast />
    @livewireScripts
</body>
</html>
