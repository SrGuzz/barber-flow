<div>
    <!-- Navbar -->
    <header class="bg-[#0D1117] shadow-md sticky top-0 z-50 w-full">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4 py-3">
            <div class="text-2xl font-bold text-[#CEAD77]">
                <img src="{{ asset('images/logo3.png') }}" alt="Logo" class="w-3/6 inline-block mr-2">
            </div>
            <nav class="space-x-4 text-sm font-medium">
                <a href="#sobre" class="hover:text-white hover:opacity-85 cursor-pointer">Sobre</a>
                <a href="#funcionalidades" class="hover:text-white hover:opacity-85 cursor-pointer">Funcionalidades</a>
                <a href="{{ route('login') }}" 
                class="inline-block text-white 
                font-bold px-4 py-2 rounded border-2 hover:bg-[#131921] 
                transition-transform duration-200 ease-in-out transform hover:scale-105 hover:shadow-lg active:scale-95 cursor-pointer">
                Login</a>
                <a href="{{ route('sign-up') }}" 
                class="inline-block bg-white text-black px-4 py-2 rounded hover:bg-white hover:opacity-85 
                transition-transform duration-200 ease-in-out transform hover:scale-105 hover:shadow-lg active:scale-95cursor-pointer">
                Cadastre-se</a>
            </nav>
        </div>
    </header>

    <!-- Bloco com imagem de fundo -->
<div class="bg-cover bg-center bg-no-repeat" style="background-image: url('/images/background.jpg');">
    <!-- Hero -->
    <section class="text-center py-20 bg-[#0D1117]/70 text-white px-6">
        <h1 class="text-4xl md:text-5xl text-[#CEAD77] font-bold mb-6">O sistema ideal para sua barbearia</h1>
        <p class="max-w-2xl mx-auto text-lg mb-8">Controle financeiro, gestão de barbeiros, planos mensais e muito mais — tudo em um só lugar.</p>
        <a href="/login" class="inline-block bg-white text-[#0D1117] px-6 py-3 rounded-full font-bold transition-transform duration-200 ease-in-out transform hover:scale-105 hover:shadow-lg active:scale-95 hover:bg-gray-200 cursor-pointer">
            Comece Agora
        </a>
    </section>

    <!-- Sobre -->
    <section id="sobre" class="py-16 px-6 bg-[#0D1117]/70">
        <div class="max-w-5xl mx-auto text-center">
            <h2 class="text-3xl text-[#CEAD77] font-bold mb-6">Sobre o Barber Flow</h2>
            <p class="text-white text-lg font-semibold leading-relaxed">
                O Barber Flow é uma solução completa para o controle financeiro e operacional do seu estabelecimento.
                Acompanhe o faturamento diário, semanal e mensal, visualize ganhos por barbeiro, e diferencie cortes avulsos dos planos mensais.
                Tome decisões estratégicas com base em dados reais, otimize os lucros e ofereça um atendimento de excelência.
            </p>
        </div>
    </section>

    <!-- Funcionalidades -->
    <section id="funcionalidades" class="py-16 px-6 bg-[#0D1117]/70">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl text-[#CEAD77] font-bold text-center mb-10">Funcionalidades Principais</h2>
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div class="bg-[#29313D] p-6 rounded-lg shadow transition-transform duration-200 ease-in-out hover:scale-105">
                    <h3 class="text-xl font-bold mb-2">Relatórios Financeiros</h3>
                    <p>Visualize receitas e despesas em tempo real com gráficos e dados claros.</p>
                </div>
                <div class="bg-[#29313D] p-6 rounded-lg shadow transition-transform duration-200 ease-in-out hover:scale-105">
                    <h3 class="text-xl font-bold mb-2">Ganhos por Barbeiro</h3>
                    <p>Acompanhe os resultados individuais de cada profissional da equipe.</p>
                </div>
                <div class="bg-[#29313D] p-6 rounded-lg shadow transition-transform duration-200 ease-in-out hover:scale-105">
                    <h3 class="text-xl font-bold mb-2">Gestão de Planos</h3>
                    <p>Diferencie clientes com mensalidades dos atendimentos avulsos.</p>
                </div>
            </div>
        </div>
    </section>

</div>

    <!-- CTA -->
    <section class="py-20 bg-[#29313D] text-white text-center px-6">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">Simplifique sua gestão agora mesmo</h2>
        <p class="text-lg mb-8">Cadastre-se gratuitamente e leve sua barbearia a outro nível!</p>
        <a href="/sign-up" class="inline-block bg-white text-[#0D1117] px-6 py-3 rounded-full text-lg font-bold transition-transform duration-200 ease-in-out transform hover:scale-105 hover:shadow-lg active:scale-95 hover:bg-gray-200 cursor-pointer">
            Criar Conta
        </a>
    </section>

    <!-- Rodapé -->
    <footer class="bg-[#0D1117] text-white text-center py-6">
        <p>&copy; {{ date('Y') }} Barber Flow. Todos os direitos reservados.</p>
    </footer>
</div>
