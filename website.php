<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Guide - Hostel Management System</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&family=Poppins:wght@400;500;600&display=swap');

        :root {
            --pink: #c2185b;
            --pink-dark: #b84884;
            --purple: #7b1fa2;
            --bg: #fce4ec;
            --card: #fff;
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: #37474f;
            line-height: 1.7;
            padding: 20px;
        }

        .container { max-width: 860px; margin: 0 auto; }

        .hero {
            background: linear-gradient(135deg, var(--pink-dark), var(--purple));
            color: #fff;
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            margin-bottom: 32px;
        }

        .hero h1 {
            font-family: 'Nunito', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .hero p { opacity: 0.85; font-size: 0.95rem; }

        .card {
            background: var(--card);
            border-radius: 12px;
            padding: 28px;
            margin-bottom: 24px;
            box-shadow: 0 2px 12px rgba(194, 37, 100, 0.1);
        }

        .card h2 {
            font-family: 'Nunito', sans-serif;
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--pink);
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f8bbd0;
        }

        .step {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
            align-items: flex-start;
        }

        .step-num {
            width: 36px;
            height: 36px;
            background: var(--pink);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-family: 'Nunito', sans-serif;
            flex-shrink: 0;
            font-size: 1rem;
        }

        .step-content h3 {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--pink-dark);
            margin-bottom: 4px;
        }

        .step-content p, .step-content li {
            font-size: 0.88rem;
            color: #546e7a;
        }

        .step-content ul {
            padding-left: 18px;
            margin-top: 6px;
        }

        code {
            background: #fce4ec;
            color: var(--pink-dark);
            padding: 2px 8px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
        }

        pre {
            background: #1a1a2e;
            color: #e8d5f0;
            padding: 16px 20px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            font-size: 0.82rem;
            overflow-x: auto;
            margin-top: 10px;
            line-height: 1.6;
        }

        .file-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .file-item {
            background: #fce4ec;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.82rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .file-item code {
            background: none;
            padding: 0;
            color: var(--pink-dark);
            font-weight: 600;
        }

        .cred-box {
            background: linear-gradient(135deg, #fce4ec, #f3e5f5);
            border: 2px solid var(--pink);
            border-radius: 10px;
            padding: 16px 20px;
            font-size: 0.9rem;
        }

        .cred-box strong { color: var(--pink-dark); }

        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-green { background: #e8f5e9; color: #2e7d32; }
        .badge-blue  { background: #e3f2fd; color: #0277bd; }

        .tip {
            background: #e8f5e9;
            border-left: 4px solid #2e7d32;
            padding: 10px 14px;
            border-radius: 0 8px 8px 0;
            font-size: 0.85rem;
            color: #2e7d32;
            margin-top: 10px;
        }

        @media (max-width: 600px) {
            .hero { padding: 24px 16px; }
            .hero h1 { font-size: 1.6rem; }
            .card { padding: 20px 16px; }
        }
    </style>
</head>
<body>
<div class="container">

    <!-- Hero -->
    <div class="hero">
        <div style="font-size:3rem; margin-bottom:12px;">🏠</div>
        <h1>Hostel Management System</h1>
        <p>College Girls Hostel • PHP + MySQL + HTML/CSS</p>
        <p style="margin-top:8px; font-size:0.82rem; opacity:0.7;">Step-by-step XAMPP Setup Guide</p>
    </div>

    <!-- Project Files -->
    <div class="card">
        <h2>📁 Project File Structure</h2>
        <p style="font-size:0.88rem; margin-bottom:12px; color:#546e7a;">
            All files go inside: <code>C:\xampp\htdocs\hostel\</code>
        </p>
        <div class="file-list">
            <div class="file-item">📄 <code>index.php</code> — Login Page</div>
            <div class="file-item">📊 <code>dashboard.php</code> — Dashboard</div>
            <div class="file-item">➕ <code>add_student.php</code> — Add Student</div>
            <div class="file-item">👀 <code>view_students.php</code> — Student List</div>
            <div class="file-item">✏️ <code>edit_student.php</code> — Edit Student</div>
            <div class="file-item">🗑️ <code>delete_student.php</code> — Delete Student</div>
            <div class="file-item">🚪 <code>room_management.php</code> — Rooms</div>
            <div class="file-item">💰 <code>fee_management.php</code> — Fees</div>
            <div class="file-item">📋 <code>complaints.php</code> — Complaints</div>
            <div class="file-item">🧭 <code>sidebar.php</code> — Navigation</div>
            <div class="file-item">⚙️ <code>config.php</code> — DB Connection</div>
            <div class="file-item">🚪 <code>logout.php</code> — Logout</div>
            <div class="file-item">🎨 <code>style.css</code> — Stylesheet</div>
            <div class="file-item">🗄️ <code>database.sql</code> — SQL Setup</div>
        </div>
    </div>

    <!-- Requirements -->
    <div class="card">
        <h2>📋 Requirements</h2>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <span class="badge badge-green">✅ XAMPP (Apache + MySQL)</span>
            <span class="badge badge-blue">🐘 PHP 7.4+</span>
            <span class="badge badge-blue">🗄️ MySQL 5.7+</span>
            <span class="badge badge-green">✅ Any Web Browser</span>
            <span class="badge badge-green">✅ Internet (for Google Fonts)</span>
        </div>
        <div class="tip">
            💡 Download XAMPP free from: <strong>apachefriends.org</strong>
        </div>
    </div>

    <!-- Setup Steps -->
    <div class="card">
        <h2>🚀 Setup Instructions (XAMPP)</h2>

        <div class="step">
            <div class="step-num">1</div>
            <div class="step-content">
                <h3>Install & Start XAMPP</h3>
                <p>Download and install XAMPP. Open the XAMPP Control Panel and start both <strong>Apache</strong> and <strong>MySQL</strong> services.</p>
            </div>
        </div>

        <div class="step">
            <div class="step-num">2</div>
            <div class="step-content">
                <h3>Copy Project Files</h3>
                <p>Copy the entire <code>hostel</code> folder to:</p>
                <pre>C:\xampp\htdocs\hostel\</pre>
                <p style="margin-top:8px;">Your folder should look like: <code>C:\xampp\htdocs\hostel\index.php</code></p>
            </div>
        </div>

        <div class="step">
            <div class="step-num">3</div>
            <div class="step-content">
                <h3>Create the Database</h3>
                <p>Open your browser and go to:</p>
                <pre>http://localhost/phpmyadmin</pre>
                <ul>
                    <li>Click <strong>"New"</strong> on the left sidebar</li>
                    <li>Name the database: <code>hostel_db</code></li>
                    <li>Click <strong>"Create"</strong></li>
                    <li>Click on <code>hostel_db</code> → Click <strong>"Import"</strong> tab</li>
                    <li>Choose the file <code>database.sql</code> from the hostel folder</li>
                    <li>Click <strong>"Go"</strong> — all tables will be created automatically!</li>
                </ul>
            </div>
        </div>

        <div class="step">
            <div class="step-num">4</div>
            <div class="step-content">
                <h3>Check config.php (Optional)</h3>
                <p>Open <code>config.php</code> and verify the database settings:</p>
                <pre>define('DB_HOST', 'localhost');  // usually correct
define('DB_USER', 'root');       // default XAMPP user
define('DB_PASS', '');           // empty by default
define('DB_NAME', 'hostel_db'); // must match DB name</pre>
            </div>
        </div>

        <div class="step">
            <div class="step-num">5</div>
            <div class="step-content">
                <h3>Open the Project in Browser</h3>
                <p>Go to:</p>
                <pre>http://localhost/hostel/index.php</pre>
            </div>
        </div>

        <div class="step">
            <div class="step-num">6</div>
            <div class="step-content">
                <h3>Login with Admin Credentials</h3>
                <div class="cred-box" style="margin-top:8px;">
                    <strong>Username:</strong> admin<br>
                    <strong>Password:</strong> admin123
                </div>
            </div>
        </div>

    </div>

    <!-- Features Summary -->
    <div class="card">
        <h2>✨ Features Summary</h2>
        <table style="width:100%; border-collapse:collapse; font-size:0.87rem;">
            <thead>
                <tr style="background: linear-gradient(135deg, #c2185b, #7b1fa2); color:#fff;">
                    <th style="padding:10px 14px; text-align:left; border-radius:8px 0 0 0;">Module</th>
                    <th style="padding:10px 14px; text-align:left;">Features</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom:1px solid #f8bbd0;">
                    <td style="padding:10px 14px; font-weight:600; color:#880e4f;">🔐 Authentication</td>
                    <td style="padding:10px 14px;">Admin login/logout, session-based protection</td>
                </tr>
                <tr style="border-bottom:1px solid #f8bbd0; background:#fce4ec;">
                    <td style="padding:10px 14px; font-weight:600; color:#880e4f;">📊 Dashboard</td>
                    <td style="padding:10px 14px;">Stats: students, rooms, occupied, pending fees, quick actions</td>
                </tr>
                <tr style="border-bottom:1px solid #f8bbd0;">
                    <td style="padding:10px 14px; font-weight:600; color:#880e4f;">👩‍🎓 Students</td>
                    <td style="padding:10px 14px;">Add, View, Search, Edit, Delete with validation</td>
                </tr>
                <tr style="border-bottom:1px solid #f8bbd0; background:#fce4ec;">
                    <td style="padding:10px 14px; font-weight:600; color:#880e4f;">🚪 Rooms</td>
                    <td style="padding:10px 14px;">Add rooms, visual availability grid, allocate/check-out, auto occupancy</td>
                </tr>
                <tr style="border-bottom:1px solid #f8bbd0;">
                    <td style="padding:10px 14px; font-weight:600; color:#880e4f;">💰 Fees</td>
                    <td style="padding:10px 14px;">Add records, toggle Paid/Pending, filter, delete, totals summary</td>
                </tr>
                <tr style="background:#fce4ec;">
                    <td style="padding:10px 14px; font-weight:600; color:#880e4f;">📋 Complaints</td>
                    <td style="padding:10px 14px;">Submit complaints, mark resolved, filter by status</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Troubleshooting -->
    <div class="card">
        <h2>🛠️ Common Troubleshooting</h2>

        <div style="margin-bottom:16px;">
            <p style="font-weight:600; color:#880e4f; font-size:0.9rem;">❌ "Access denied for user 'root'"</p>
            <p style="font-size:0.85rem;">Open <code>config.php</code> and make sure DB_PASS matches your MySQL password (empty by default in XAMPP).</p>
        </div>

        <div style="margin-bottom:16px;">
            <p style="font-weight:600; color:#880e4f; font-size:0.9rem;">❌ "No database selected" or table errors</p>
            <p style="font-size:0.85rem;">Make sure you imported <code>database.sql</code> into phpMyAdmin correctly, and DB_NAME is <code>hostel_db</code>.</p>
        </div>

        <div style="margin-bottom:16px;">
            <p style="font-weight:600; color:#880e4f; font-size:0.9rem;">❌ Page shows blank or PHP errors</p>
            <p style="font-size:0.85rem;">Make sure Apache is running in XAMPP. Check that all files are in the correct folder.</p>
        </div>

        <div>
            <p style="font-weight:600; color:#880e4f; font-size:0.9rem;">❌ "Invalid username or password"</p>
            <p style="font-size:0.85rem;">The default admin is created during SQL import. Username: <strong>admin</strong>, Password: <strong>admin123</strong>.</p>
        </div>
    </div>

    <!-- Footer -->
    <div style="text-align:center; padding:20px; color:#90a4ae; font-size:0.82rem;">
        🏠 College Girls Hostel Management System • Built with PHP + MySQL + HTML/CSS
    </div>

</div>
</body>
</html>
