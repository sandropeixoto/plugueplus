<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                    <div>
                        <h2 class="h4 mb-1">Categorias cadastradas</h2>
                        <p class="text-muted mb-0">Organize os serviços por temas e defina ícones representativos.</p>
                    </div>
                    <a class="btn btn-outline-primary" href="admin.php?section=categorias">
                        Nova categoria
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Ícone</th>
                                <th scope="col" class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (count($categorias) === 0): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Nenhuma categoria cadastrada até o momento.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categorias as $categoria): ?>
                                <tr>
                                    <td><?= htmlspecialchars($categoria['nome']) ?></td>
                                    <td><?= htmlspecialchars($categoria['icone'] ?? '-') ?></td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-soft-primary me-2" href="admin.php?section=categorias&amp;edit=<?= (int) $categoria['id'] ?>">
                                            Editar
                                        </a>
                                        <form method="post" action="admin.php?section=categorias" class="d-inline" onsubmit="return confirm('Deseja remover esta categoria?');">
                                            <input type="hidden" name="action" value="delete_categoria">
                                            <input type="hidden" name="id" value="<?= (int) $categoria['id'] ?>">
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

    <div class="col-12 col-xl-6">
        <div class="card">
            <div class="card-body p-4">
                <?php if ($editingCategoria): ?>
                    <h2 class="h4 mb-3">Editar categoria</h2>
                    <form method="post" action="admin.php?section=categorias">
                        <input type="hidden" name="action" value="update_categoria">
                        <input type="hidden" name="id" value="<?= (int) $editingCategoria['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label" for="categoria-nome">Nome</label>
                            <input class="form-control" type="text" id="categoria-nome" name="nome" value="<?= htmlspecialchars($editingCategoria['nome']) ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="categoria-icone">Ícone (opcional)</label>
                            <input class="form-control" type="text" id="categoria-icone" name="icone" value="<?= htmlspecialchars($editingCategoria['icone'] ?? '') ?>" placeholder="Ex.: mdi-tools">
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">Salvar alterações</button>
                            <a class="btn btn-light" href="admin.php?section=categorias">Cancelar</a>
                        </div>
                    </form>
                <?php else: ?>
                    <h2 class="h4 mb-3">Nova categoria</h2>
                    <form method="post" action="admin.php?section=categorias">
                        <input type="hidden" name="action" value="create_categoria">
                        <div class="mb-3">
                            <label class="form-label" for="nova-categoria-nome">Nome</label>
                            <input class="form-control" type="text" id="nova-categoria-nome" name="nome" placeholder="Ex.: Estacionamentos" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="nova-categoria-icone">Ícone (opcional)</label>
                            <input class="form-control" type="text" id="nova-categoria-icone" name="icone" placeholder="Ex.: mdi-parking">
                        </div>
                        <button class="btn btn-primary" type="submit">Cadastrar categoria</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
