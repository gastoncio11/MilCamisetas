
    <div class="main-wrapper">
        <div class="admin-background">
            <div class="admin-container">
                
                <!-- Header del Admin -->
                <div class="admin-header">
                    <div>
                        <h1>📧 Gestionar Consultas</h1>
                        <p>Panel de administración de consultas de contacto</p>
                    </div>
                    <div class="header-actions">
                        <div class="consultas-stats">
                            <span class="stat-item nuevas">
                                📩 Nuevas: <?= count(array_filter($consultas ?? [], function($c) { return $c['leida'] == 0; })) ?>
                            </span>
                            <span class="stat-item leidas">
                                ✅ Leídas: <?= count(array_filter($consultas ?? [], function($c) { return $c['leida'] == 1; })) ?>
                            </span>
                            <span class="stat-item total">
                                📊 Total: <?= count($consultas ?? []) ?>
                            </span>
                        </div>
                        <a href="<?= base_url('operaciones') ?>" class="btn btn-secondary">
                            🏠 Volver al Panel
                        </a>
                    </div>
                </div>

                <!-- Alertas -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <span class="alert-icon">✅</span>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error">
                        <span class="alert-icon">❌</span>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php 
                // Separar consultas por estado
                $consultasNuevas = [];
                $consultasLeidas = [];
                
                if (isset($consultas) && !empty($consultas)) {
                    foreach ($consultas as $consulta) {
                        if ($consulta['leida'] == 0) {
                            $consultasNuevas[] = $consulta;
                        } else {
                            $consultasLeidas[] = $consulta;
                        }
                    }
                }
                ?>

                <!-- Sección Consultas Nuevas -->
                <div class="consultas-section">
                    <div class="section-header" onclick="toggleSection('nuevas')">
                        <h2>
                            <span class="toggle-icon" id="icon-nuevas">▼</span>
                            📩 Consultas Nuevas
                            <?php if (!empty($consultasNuevas)): ?>
                                <span class="badge badge-danger"><?= count($consultasNuevas) ?></span>
                            <?php endif; ?>
                        </h2>
                    </div>
                    <div class="section-content" id="section-nuevas">
                        <?php if (!empty($consultasNuevas)): ?>
                            <div class="consultas-grid">
                                <?php foreach ($consultasNuevas as $consulta): ?>
                                    <div class="consulta-card nueva">
                                        <div class="consulta-header">
                                            <div class="consulta-info">
                                                <h3><?= esc($consulta['asunto']) ?></h3>
                                                <div class="consulta-fecha">
                                                    📅 <?= date('d/m/Y H:i', strtotime($consulta['fecha'])) ?>
                                                </div>
                                            </div>
                                            <div class="consulta-actions">
                                                <button class="btn-action read" 
                                                        onclick="marcarLeida(<?= $consulta['id'] ?>)"
                                                        title="Marcar como leída">
                                                    ✓
                                                </button>
                                            </div>
                                        </div>
                                        <div class="consulta-content">
                                            <div class="consulta-details">
                                                <p><strong>👤 Nombre:</strong> <?= esc($consulta['nombre'] . ' ' . $consulta['apellido']) ?></p>
                                                <p><strong>📧 Email:</strong> <a href="mailto:<?= esc($consulta['email']) ?>"><?= esc($consulta['email']) ?></a></p>
                                                <p><strong>📞 Teléfono:</strong> <a href="tel:<?= esc($consulta['telefono']) ?>"><?= esc($consulta['telefono']) ?></a></p>
                                            </div>
                                            <div class="consulta-mensaje">
                                                <p><strong>💬 Mensaje:</strong></p>
                                                <p><?= nl2br(esc($consulta['mensaje'])) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <span class="empty-icon">🎉</span>
                                <h3>¡Excelente!</h3>
                                <p>No hay consultas nuevas por revisar</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Sección Consultas Leídas -->
                <div class="consultas-section">
                    <div class="section-header collapsed" onclick="toggleSection('leidas')">
                        <h2>
                            <span class="toggle-icon" id="icon-leidas">▶</span>
                            ✅ Consultas Leídas
                            <?php if (!empty($consultasLeidas)): ?>
                                <span class="badge badge-success"><?= count($consultasLeidas) ?></span>
                            <?php endif; ?>
                        </h2>
                    </div>
                    <div class="section-content collapsed" id="section-leidas">
                        <?php if (!empty($consultasLeidas)): ?>
                            <div class="consultas-grid">
                                <?php foreach ($consultasLeidas as $consulta): ?>
                                    <div class="consulta-card leida">
                                        <div class="consulta-header">
                                            <div class="consulta-info">
                                                <h3><?= esc($consulta['asunto']) ?></h3>
                                                <div class="consulta-fecha">
                                                    📅 <?= date('d/m/Y H:i', strtotime($consulta['fecha'])) ?>
                                                </div>
                                            </div>
                                            <div class="consulta-actions">
                                                <button class="btn-action unread" 
                                                        onclick="marcarNoLeida(<?= $consulta['id'] ?>)"
                                                        title="Marcar como no leída">
                                                    ↻
                                                </button>
                                            </div>
                                        </div>
                                        <div class="consulta-content">
                                            <div class="consulta-details">
                                                <p><strong>👤 Nombre:</strong> <?= esc($consulta['nombre'] . ' ' . $consulta['apellido']) ?></p>
                                                <p><strong>📧 Email:</strong> <a href="mailto:<?= esc($consulta['email']) ?>"><?= esc($consulta['email']) ?></a></p>
                                                <p><strong>📞 Teléfono:</strong> <a href="tel:<?= esc($consulta['telefono']) ?>"><?= esc($consulta['telefono']) ?></a></p>
                                            </div>
                                            <div class="consulta-mensaje">
                                                <p><strong>💬 Mensaje:</strong></p>
                                                <p><?= nl2br(esc($consulta['mensaje'])) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <span class="empty-icon">📭</span>
                                <h3>Sin consultas leídas</h3>
                                <p>Las consultas marcadas como leídas aparecerán aquí</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Si no hay consultas en absoluto -->
                <?php if (empty($consultas)): ?>
                    <div class="empty-state">
                        <span class="empty-icon">📬</span>
                        <h3>No hay consultas</h3>
                        <p>Cuando los usuarios envíen consultas, aparecerán aquí</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function toggleSection(section) {
            const content = document.getElementById('section-' + section);
            const icon = document.getElementById('icon-' + section);
            const header = document.querySelector(`[onclick="toggleSection('${section}')"]`);

            if (content.classList.contains('collapsed')) {
                content.classList.remove('collapsed');
                icon.textContent = '▼';
                header.classList.remove('collapsed');
            } else {
                content.classList.add('collapsed');
                icon.textContent = '▶';
                header.classList.add('collapsed');
            }
        }

        function marcarLeida(id) {
            if (confirm('¿Marcar esta consulta como leída?')) {
                window.location.href = `<?= base_url('admin/marcar_leida/') ?>${id}`;
            }
        }

        function marcarNoLeida(id) {
            if (confirm('¿Marcar esta consulta como no leída?')) {
                window.location.href = `<?= base_url('admin/marcar_no_leida/') ?>${id}`;
            }
        }

        // Inicializar secciones al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            // Las consultas nuevas se muestran por defecto
            // Las consultas leídas están colapsadas por defecto
            console.log('Gestionar consultas cargado correctamente');
            
            // Agregar efecto de carga suave
            const cards = document.querySelectorAll('.consulta-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });

        // Función para confirmar acciones
        function confirmarAccion(mensaje, callback) {
            if (confirm(mensaje)) {
                callback();
            }
        }

        // Función para mostrar notificaciones
        function mostrarNotificacion(mensaje, tipo = 'success') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${tipo}`;
            notification.innerHTML = `
                <span class="alert-icon">${tipo === 'success' ? '✅' : '❌'}</span>
                ${mensaje}
            `;
            
            const container = document.querySelector('.admin-container');
            container.insertBefore(notification, container.firstChild);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        // Función para filtrar consultas (opcional)
        function filtrarConsultas(termino) {
            const cards = document.querySelectorAll('.consulta-card');
            const terminoLower = termino.toLowerCase();
            
            cards.forEach(card => {
                const texto = card.textContent.toLowerCase();
                if (texto.includes(terminoLower)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Atajos de teclado
        document.addEventListener('keydown', function(e) {
            // Ctrl + 1 para mostrar/ocultar nuevas
            if (e.ctrlKey && e.key === '1') {
                e.preventDefault();
                toggleSection('nuevas');
            }
            
            // Ctrl + 2 para mostrar/ocultar leídas
            if (e.ctrlKey && e.key === '2') {
                e.preventDefault();
                toggleSection('leidas');
            }
        });
    </script>