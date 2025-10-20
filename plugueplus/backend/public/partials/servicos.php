<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                    <div>
                        <h2 class="h4 mb-1">Serviços cadastrados</h2>
                        <p class="text-muted mb-0">Gerencie oficinas, lojas e parceiros disponíveis para a comunidade PluguePlus.</p>
                    </div>
                    <a class="btn btn-outline-primary" href="admin.php?section=servicos">
                        Novo serviço
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Categoria</th>
                                <th scope="col">Telefone</th>
                                <th scope="col">Site</th>
                                <th scope="col" class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (count($servicos) === 0): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Nenhum serviço cadastrado até o momento.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($servicos as $servico): ?>
                                <tr>
                                    <td>
                                        <span class="fw-semibold d-block"><?= htmlspecialchars($servico['nome']) ?></span>
                                        <?php if (!empty($servico['endereco'])): ?>
                                            <small class="text-muted"><?= htmlspecialchars($servico['endereco']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($servico['categoria_nome'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($servico['telefone'] ?? '-') ?></td>
                                    <td>
                                        <?php if (!empty($servico['site'])): ?>
                                            <a href="<?= htmlspecialchars($servico['site']) ?>" target="_blank" rel="noopener" class="link-primary">Visitar</a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-soft-primary me-2" href="admin.php?section=servicos&amp;edit=<?= (int) $servico['id'] ?>">
                                            Editar
                                        </a>
                                        <form method="post" action="admin.php?section=servicos" class="d-inline" onsubmit="return confirm('Deseja remover este serviço?');">
                                            <input type="hidden" name="action" value="delete_servico">
                                            <input type="hidden" name="id" value="<?= (int) $servico['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body p-4">
                <?php if ($editingServico): ?>
                    <h2 class="h4 mb-3">Editar serviço</h2>
                    <form method="post" action="admin.php?section=servicos">
                        <input type="hidden" name="action" value="update_servico">
                        <input type="hidden" name="id" value="<?= (int) $editingServico['id'] ?>">
                        <?php include __DIR__ . '/servicos_form.php'; ?>
                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-primary" type="submit">Salvar alterações</button>
                            <a class="btn btn-light" href="admin.php?section=servicos">Cancelar</a>
                        </div>
                    </form>
                <?php else: ?>
                    <h2 class="h4 mb-3">Novo serviço</h2>
                    <form method="post" action="admin.php?section=servicos">
                        <input type="hidden" name="action" value="create_servico">
                        <?php $editingServico = null; include __DIR__ . '/servicos_form.php'; ?>
                        <button class="btn btn-primary mt-3" type="submit">Cadastrar serviço</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
