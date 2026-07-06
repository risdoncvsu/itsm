<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexora | Compliance Tracking</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #1B365D;
            overflow-x: hidden; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /*==========================
            HEADER & NAVIGATION
        ===========================*/
        .header {
            height: 100px;
            background: #0B1E3D;
            display: flex;
            align-items: center;
            justify-content: space-between; 
            padding-right: 48px; 
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .nexora-logo {
            display: block;
            margin: 16px 0 16px 24px; 
            height: 64px; 
            transition: .3s ease;
        }

        .nexora-logo:hover {
            transform: scale(1.02);
        }

        .nexora-logo img {
            height: 100%;
            object-fit: contain;
        }

        .header-controls {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .nav-links {
            display: flex;
            gap: 24px;
        }

        .nav-links a {
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            opacity: 0.7;
            transition: all 0.2s;
        }

        .nav-links a:hover {
            opacity: 1;
        }

        .nav-links a.active {
            opacity: 1;
            color: #60A5FA; 
            font-weight: 700; 
        }

        .user-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            cursor: pointer;
            transition: all 0.2s;
        }

        .user-icon:hover {
            color: #60A5FA;
            transform: scale(1.05);
        }

        .user-icon svg {
            width: 36px;
            height: 36px;
        }

        /*==========================
            MAIN DASHBOARD LAYOUT
        ===========================*/
        .dashboard-main {
            padding: 32px 48px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            flex: 1;
        }

        /* Page Title Bar */
        .page-title-bar {
            background: #EAEef4;
            border-radius: 12px;
            padding: 24px 32px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .page-title-bar h1 {
            color: #111827;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        /* Content Panel */
        .content-panel {
            background: #Dbe3ec; /* Light blue-gray background */
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            min-height: 500px;
        }

        /* Tabs Navigation */
        .tabs-nav {
            display: flex;
            background: #f1f5f9;
            padding: 0 24px;
            border-bottom: 2px solid #cbd5e1;
        }

        .tab {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 20px 24px;
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: all 0.2s;
        }

        .tab svg {
            width: 18px;
            height: 18px;
        }

        .tab:hover {
            color: #334155;
        }

        .tab.active {
            color: #0B1E3D;
            border-bottom-color: #0B1E3D;
        }

        /* Toolbar (Add Button & Search) */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 32px;
        }

        .btn-add {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-add:hover {
            background: #1d4ed8;
        }

        .btn-add svg {
            width: 16px;
            height: 16px;
        }

        .search-filter-group {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .search-box {
            position: relative;
        }

        .search-box svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            width: 16px;
            height: 16px;
        }

        .search-box input {
            background: #e2e8f0;
            border: none;
            padding: 10px 16px 10px 36px;
            border-radius: 8px;
            width: 250px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: #334155;
            outline: none;
        }

        .search-box input::placeholder {
            color: #94a3b8;
        }

        .filter-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            border: none;
            color: #0f172a;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
        }

        .filter-btn svg {
            width: 20px;
            height: 20px;
        }

        /*==========================
            CARDS GRID
        ===========================*/
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            padding: 0 32px 32px 32px;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
        }

        .card-header h3 {
            font-size: 16px;
            color: #111827;
            margin-bottom: 4px;
            font-weight: 600;
            line-height: 1.2;
        }

        .card-header p {
            font-size: 12px;
            color: #64748b;
        }

        .progress-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .progress-bar {
            flex: 1;
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #1e3a8a;
            border-radius: 4px;
        }

        .progress-text {
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            min-width: 32px;
            text-align: right;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-align: center;
            width: fit-content;
        }

        .badge.active { background: #dcfce7; color: #166534; }
        .badge.urgent { background: #fee2e2; color: #991b1b; }
        .badge.completed { background: #dcfce7; color: #166534; }
        .badge.pending { background: #fef3c7; color: #b45309; }

        .btn-view {
            margin-top: auto;
            width: 100%;
            padding: 8px 0;
            background: transparent;
            border: 1px solid #000000;
            border-radius: 6px;
            color: #000000;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-view:hover {
            background: #f1f5f9;
        }

    </style>
</head>

<body>

    <header class="header">
        <a href="#" class="nexora-logo">
            <img src="{{ asset('images/Nexora_ERP_Logo.png') }}" alt="Nexora Logo">
        </a>
        
        <div class="header-controls">
            <nav class="nav-links">
                <a href="#">Registration</a>
                <a href="#">User Management</a>
                <a href="#">Service Desk</a>
                <a href="#" class="active">Compliance Tracking</a>
                <a href="#">Risk Management</a>
            </nav>

            <div class="user-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-3.314 0-10 1.686-10 5v3h20v-3c0-3.314-6.686-5-10-5z"/>
                </svg>
            </div>
        </div>
    </header>
    
    <main class="dashboard-main">
        
        <div class="page-title-bar">
            <h1>Compliance Tracking</h1>
        </div>

        <div class="content-panel">
            
            <div class="tabs-nav">
                <a href="#" class="tab active">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    Compliance Requirements
                </a>
                <a href="#" class="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.5 2H8.6c-.4 0-.8.2-1.1.5-.3.3-.5.7-.5 1.1v12.8c0 .4.2.8.5 1.1.3.3.7.5 1.1.5h9.8c.4 0 .8-.2 1.1-.5.3-.3.5-.7.5-1.1V6.5L15.5 2z"></path><path d="M3 7.6v12.8c0 .4.2.8.5 1.1.3.3.7.5 1.1.5h9.8"></path></svg>
                    Audits & Inspections
                </a>
                <a href="#" class="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Permits & Licenses
                </a>
                <a href="#" class="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    Risk Assessment
                </a>
                <a href="#" class="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                    Documents
                </a>
            </div>

            <div class="toolbar">
                <button class="btn-add">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Add new
                </button>

                <div class="search-filter-group">
                    <div class="search-box">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" placeholder="Search">
                    </div>
                    <button class="filter-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4c0-.55.45-1 1-1h16c.55 0 1 .45 1 1 0 .25-.1.5-.28.7l-7.22 8.67V19l-3 2v-7.63L3.28 4.7C3.1 4.5 3 4.25 3 4z"></path></svg>
                        All
                    </button>
                </div>
            </div>

            <div class="cards-grid">
                
                <div class="card">
                    <div class="card-header">
                        <h3>Data Privacy</h3>
                        <p>All Staff</p>
                    </div>
                    <div class="progress-section">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 68%;"></div>
                        </div>
                        <span class="progress-text">68%</span>
                    </div>
                    <span class="badge active">Active</span>
                    <button class="btn-view">View</button>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Workplace Safety</h3>
                        <p>All Staff</p>
                    </div>
                    <div class="progress-section">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 97%;"></div>
                        </div>
                        <span class="progress-text">97%</span>
                    </div>
                    <span class="badge urgent">Urgent</span>
                    <button class="btn-view">View</button>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Code of Conduct</h3>
                        <p>All Staff</p>
                    </div>
                    <div class="progress-section">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 100%;"></div>
                        </div>
                        <span class="progress-text">100%</span>
                    </div>
                    <span class="badge completed">Completed</span>
                    <button class="btn-view">View</button>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Cybersecurity<br>Awareness</h3>
                        <p>All Staff</p>
                    </div>
                    <div class="progress-section">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 0%;"></div>
                        </div>
                        <span class="progress-text">0%</span>
                    </div>
                    <span class="badge pending">Pending Review</span>
                    <button class="btn-view">View</button>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Financial Audit</h3>
                        <p>All Staff</p>
                    </div>
                    <div class="progress-section">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 100%;"></div>
                        </div>
                        <span class="progress-text">100%</span>
                    </div>
                    <span class="badge completed">Completed</span>
                    <button class="btn-view">View</button>
                </div>

            </div>
        </div>

    </main>

</body>
</html>