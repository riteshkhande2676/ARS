<?php
/**
 * Admin Panel - View Contact Messages
 * ARS ENGINEERS Solar Energy Website
 */

// Include database configuration
require_once __DIR__ . '/config/database.php';

// Handle AJAX requests for marking as read/deleting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    try {
        $db = getDB();

        if ($_POST['action'] === 'mark_read' && isset($_POST['id'])) {
            $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = :id");
            $stmt->execute([':id' => $_POST['id']]);
            echo json_encode(['success' => true]);
            exit;
        }

        if ($_POST['action'] === 'delete' && isset($_POST['id'])) {
            $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = :id");
            $stmt->execute([':id' => $_POST['id']]);
            echo json_encode(['success' => true]);
            exit;
        }

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// Fetch all messages
try {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll();
    $total_messages = count($messages);
    $unread_messages = count(array_filter($messages, function ($m) {
        return !$m['is_read']; }));
} catch (Exception $e) {
    $error_message = "Error fetching messages: " . $e->getMessage();
    $messages = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - ARS ENGINEERS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin/styles.css">
</head>

<body>
    <div class="admin-container">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-content">
                <h1>🌞 ARS ENGINEERS - Admin Panel</h1>
                <div class="header-stats">
                    <div class="stat-badge">
                        <span class="stat-label">Total Messages:</span>
                        <span class="stat-value"><?php echo $total_messages; ?></span>
                    </div>
                    <div class="stat-badge unread">
                        <span class="stat-label">Unread:</span>
                        <span class="stat-value"><?php echo $unread_messages; ?></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="panel-header">
                <h2>Contact Messages</h2>
                <button onclick="location.reload()" class="btn-refresh">🔄 Refresh</button>
            </div>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <?php if (empty($messages)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h3>No messages yet</h3>
                    <p>Customer messages will appear here when they contact you through the website.</p>
                </div>
            <?php else: ?>
                <div class="messages-table-container">
                    <table class="messages-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $msg): ?>
                                <tr class="<?php echo $msg['is_read'] ? 'read' : 'unread'; ?>"
                                    data-id="<?php echo $msg['id']; ?>">
                                    <td><?php echo htmlspecialchars($msg['id']); ?></td>
                                    <td class="date-cell">
                                        <?php echo date('M d, Y', strtotime($msg['created_at'])); ?>
                                        <span class="time"><?php echo date('H:i', strtotime($msg['created_at'])); ?></span>
                                    </td>
                                    <td class="name-cell"><?php echo htmlspecialchars($msg['name']); ?></td>
                                    <td class="email-cell">
                                        <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>">
                                            <?php echo htmlspecialchars($msg['email']); ?>
                                        </a>
                                    </td>
                                    <td class="phone-cell">
                                        <a href="tel:<?php echo htmlspecialchars($msg['phone']); ?>">
                                            📞 <?php echo htmlspecialchars($msg['phone']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($msg['subject'] ?: '-'); ?></td>
                                    <td class="message-cell">
                                        <div class="message-preview">
                                            <?php echo htmlspecialchars(substr($msg['message'], 0, 100)); ?>
                                            <?php if (strlen($msg['message']) > 100): ?>...<?php endif; ?>
                                        </div>
                                        <div class="message-full" style="display: none;">
                                            <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                                        </div>
                                        <button class="btn-expand" onclick="toggleMessage(this)">Read More</button>
                                    </td>
                                    <td>
                                        <?php if ($msg['is_read']): ?>
                                            <span class="status-badge read">Read</span>
                                        <?php else: ?>
                                            <span class="status-badge unread">New</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions-cell">
                                        <?php if (!$msg['is_read']): ?>
                                            <button class="btn-action btn-mark-read" onclick="markAsRead(<?php echo $msg['id']; ?>)"
                                                title="Mark as Read">
                                                ✓
                                            </button>
                                        <?php endif; ?>
                                        <button class="btn-action btn-delete" onclick="deleteMessage(<?php echo $msg['id']; ?>)"
                                            title="Delete">
                                            🗑️
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>

        <!-- Footer -->
        <footer class="admin-footer">
            <p>&copy; <?php echo date('Y'); ?> ARS ENGINEERS. All rights reserved.</p>
            <a href="index.html" class="btn-back">← Back to Website</a>
        </footer>
    </div>

    <script>
        function toggleMessage(btn) {
            const cell = btn.closest('.message-cell');
            const preview = cell.querySelector('.message-preview');
            const full = cell.querySelector('.message-full');

            if (full.style.display === 'none') {
                preview.style.display = 'none';
                full.style.display = 'block';
                btn.textContent = 'Show Less';
            } else {
                preview.style.display = 'block';
                full.style.display = 'none';
                btn.textContent = 'Read More';
            }
        }

        function markAsRead(id) {
            if (!confirm('Mark this message as read?')) return;

            fetch('admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=mark_read&id=${id}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    alert('Error marking message as read');
                    console.error(error);
                });
        }

        function deleteMessage(id) {
            if (!confirm('Are you sure you want to delete this message? This action cannot be undone.')) return;

            fetch('admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=delete&id=${id}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    alert('Error deleting message');
                    console.error(error);
                });
        }

        // Auto-refresh every 30 seconds
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>

</html>