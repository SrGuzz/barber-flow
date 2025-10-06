<?php

namespace App\Http\Controllers;

abstract class Controller
{
    // Sugestão: transformar este Controller em um BaseController que injeta dependências comuns.
    // Justificativa: centralizar lógica compartilhada (ex: transformadores, helpers de resposta) facilita testes
    // e reduz duplicação entre controllers, melhorando manutenibilidade.

    // Sugestão: adicionar PHPDoc para descrever contratos herdados pelos controllers.
    // Benefício: melhora autocompletar na IDE e facilita documentação automática para desenvolvedores novos.

    // Sugestão: considerar uso de traits para funcionalidades reutilizáveis (ex: apiResponses, authorizes).
    // Benefício: mantém controllers pequenos e respeita o SRP (Single Responsibility Principle).

    // Sugestão: disponibilizar um helper protected para respostas JSON padronizadas (success/error).
    // Benefício: consistência nas APIs e redução de repetição de código nas actions.
}
