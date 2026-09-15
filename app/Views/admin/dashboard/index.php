<?= view('admin/layout/header', ['title' => 'Dashboard & Métricas', 'storeName' => $storeName, 'themeColor' => $themeColor]) ?>

<style>
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .metric-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }

    .metric-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        background: #f1f5f9;
    }

    .metric-data h3 {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1.1;
    }

    .metric-data span {
        font-size: 0.82rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .charts-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        margin-bottom: 32px;
    }

    .panel-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }

    .panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .panel-header h3 {
        font-size: 1.1rem;
        font-weight: 700;
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }

    th {
        text-align: left;
        padding: 12px 16px;
        background: #f8fafc;
        color: var(--text-muted);
        font-weight: 700;
        font-size: 0.78rem;
        text-transform: uppercase;
        border-bottom: 1px solid var(--border);
    }

    td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
        color: #334155;
    }

    tr:last-child td {
        border-bottom: none;
    }

    .badge-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        background: #dcfce7;
        color: #166534;
    }

    @media (max-width: 900px) {
        .charts-row { grid-template-columns: 1fr; }
    }
</style>

<!-- CARDS DE MÉTRICAS -->
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-icon" style="background: #fdf2f8; color: #db2777;">👆</div>
        <div class="metric-data">
            <h3><?= esc($totalClicks) ?></h3>
            <span>Cliques em Produtos</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon" style="background: #eff6ff; color: #2563eb;">🛍️</div>
        <div class="metric-data">
            <h3><?= esc($totalOrders) ?></h3>
            <span>Pedidos Gerados</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon" style="background: #f0fdf4; color: #16a34a;">🧁</div>
        <div class="metric-data">
            <h3><?= esc($totalProducts) ?></h3>
            <span>Produtos Ativos</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon" style="background: #fefce8; color: #ca8a04;">🏷️</div>
        <div class="metric-data">
            <h3><?= esc($totalCategories) ?></h3>
            <span>Categorias</span>
        </div>
    </div>
</div>

<!-- GRÁFICO DE POPULARIDADE E TOP PRODUTOS -->
<div class="charts-row">
    <div class="panel-card">
        <div class="panel-header">
            <h3>📈 Itens Mais Populares (Rastreamento de Cliques)</h3>
            <span style="font-size: 0.8rem; color: var(--text-muted);">Métricas em tempo real</span>
        </div>
        <div>
            <canvas id="clicksChart" height="130"></canvas>
        </div>
    </div>

    <div class="panel-card">
        <div class="panel-header">
            <h3>🏆 Ranking de Cliques</h3>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Cliques</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($popularProducts)): ?>
                        <?php foreach ($popularProducts as $prod): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($prod['name']) ?></strong>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);"><?= esc($prod['category_name'] ?? 'Geral') ?></div>
                                </td>
                                <td><span class="badge-status"><?= esc($prod['total_clicks']) ?> cliques</span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                Nenhum clique rastreado ainda.<br>Navegue pelo catálogo público para gerar métricas.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- HISTÓRICO DE PEDIDOS RECENTES -->
<div class="panel-card">
    <div class="panel-header">
        <h3>🛒 Últimos Pedidos Registrados</h3>
        <span style="font-size: 0.8rem; color: var(--text-muted);">Enviados via WhatsApp</span>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Cliente</th>
                    <th>Telefone</th>
                    <th>Endereço / Bairro</th>
                    <th>Pagamento</th>
                    <th>Total</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php if (! empty($recentOrders)): ?>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td><strong>#<?= esc($order['id']) ?></strong></td>
                            <td><?= esc($order['customer_name']) ?></td>
                            <td><?= esc($order['customer_phone']) ?></td>
                            <td><?= esc($order['street']) ?>, <?= esc($order['number']) ?> - <?= esc($order['neighborhood']) ?></td>
                            <td><?= esc($order['payment_method']) ?></td>
                            <td><strong>R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></strong></td>
                            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            Nenhum pedido registrado ainda.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const labels = <?= $chartLabels ?>;
    const dataVals = <?= $chartData ?>;

    const ctx = document.getElementById('clicksChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels.length ? labels : ['Sem dados'],
            datasets: [{
                label: 'Total de Cliques',
                data: dataVals.length ? dataVals : [0],
                backgroundColor: '<?= esc($themeColor) ?>cc',
                borderColor: '<?= esc($themeColor) ?>',
                borderWidth: 1.5,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            layout: {
                padding: {
                    top: 10,
                    right: 8,
                    bottom: 4,
                    left: 4
                }
            },
            plugins: {
                legend: {
                    display: false,
                    position: 'top'
                },
                tooltip: {
                    position: 'nearest'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>

<?= view('admin/layout/footer') ?>
