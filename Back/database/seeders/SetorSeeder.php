<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setor;

class SetorSeeder extends Seeder
{
    public function run(): void
    {
        $setores = [
            'Logística', 'Estoque', 'Financeiro', 'Recursos Humanos', 'Atendimento',
            'Comercial', 'Vendas', 'Marketing', 'Publicidade', 'Jurídico',
            'Compras', 'Expedição', 'Almoxarifado', 'Engenharia', 'Produção',
            'Qualidade', 'Suporte Técnico', 'TI', 'Dev Software', 'Segurança Info',
            'Contabilidade', 'Faturamento', 'Tesouraria', 'Planejamento Estratégico',
            'P&D', 'Operações', 'Transportes', 'Manutenção', 'Projetos', 'Auditoria',
            'Controladoria', 'Call Center', 'SAC', 'Comunicação Interna', 'Eventos',
            'Imprensa', 'Relações Institucionais', 'Sustentabilidade', 'Facilities',
            'Limpeza', 'Portaria', 'Segurança Patrimonial', 'Engenharia Civil',
            'Engenharia Mecânica', 'Engenharia Elétrica', 'Engenharia Produção',
            'TI - Infraestrutura', 'TI - Desenvolvimento', 'Design Gráfico', 'Criação',
            'E-commerce', 'Comercial Externo', 'Comercial Interno', 'Pós-vendas',
            'Pré-vendas', 'Logística Reversa', 'Atendimento Digital', 'Atendimento Telefônico',
            'T&D', 'Recrutamento e Seleção', 'DP', 'Folha de Pagamento', 'Benefícios',
            'Segurança do Trabalho', 'Medicina do Trabalho', 'Jurídico Trabalhista',
            'Jurídico Contratual', 'Compliance', 'Governança', 'TI - Suporte',
            'Análise de Dados', 'BI', 'CRM', 'Mídias Sociais', 'SEO', 'Digital Performance',
            'Mobile', 'QA', 'UX/UI', 'Parcerias', 'Franquias', 'Licitações', 'Cadastro Produtos',
            'Logística Interna', 'Logística Externa', 'Transportadora', 'Gerência Geral',
            'Diretoria', 'Presidência', 'Conselho Administrativo', 'Investimentos',
            'Planejamento Financeiro', 'Estratégia Comercial', 'Inovação', 'Laboratório',
            'Pesquisa de Mercado'
        ];

        foreach ($setores as $nome) {
            Setor::create(['nome' => $nome]);
        }
    }
}
