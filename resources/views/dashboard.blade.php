<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexora | Dashboard</title>
    <link rel="icon" type="image/x-icon" href="images/nexora-icon.ico">
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
            overflow: hidden; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /*==========================
            SPLASH SCREEN
        ===========================*/
        #splash {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            z-index: 99999;
            transition: opacity .6s ease;
        }

        .circle {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #0B1E3D;
            border-radius: 50%;
            animation: spread .5s ease-out forwards;
        }

        @keyframes spread {
            0% { transform: scale(0); }
            100% { transform: scale(350); }
        }

        .brand {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 5;
        }

        .logo {
            width: 132px;
            height: 132px;
            opacity: 0;
            transform: scale(0) rotate(0deg);
            animation: logoIntro 0.5s ease forwards 0.8s, logoMove .8s ease forwards 2s;
        }

        @keyframes logoIntro {
            0% { opacity: 0; transform: scale(0) rotate(0deg); }
            100% { opacity: 1; transform: scale(1) rotate(360deg); }
        }

        @keyframes logoMove {
            from { transform: translateX(0); }
            to { transform: translateX(-170px); }
        }

        .banner {
            position: absolute;
            margin-left: 175px;
            width: 0;
            opacity: 0;
            transform: translateX(-80px);
            animation: bannerReveal .8s ease forwards 2.25s;
        }

        @keyframes bannerReveal {
            0% { width: 0; opacity: 0; transform: translateX(-150px); }
            100% { width: 420px; opacity: 1; transform: translateX(10px); }
        }

        /*==========================
            LAYOUT & ANIMATIONS
        ===========================*/
        @keyframes showPage {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .app-container {
            opacity: 0;
            animation: showPage .8s ease forwards 4.1s;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
        }

        /*==========================
            HEADER & NAVIGATION
        ===========================*/
        .header {
            height: 128px;
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
            margin: 16px 0 16px 16px; 
            height: 96px; 
            transition: .3s ease;
        }

        .nexora-logo:hover {
            transform: scale(1.02);
        }

        .nexora-logo img {
            height: 100%;
            object-fit: contain;
            transition: .3s ease;
        }

        /* New container to group Nav Links and User Icon on the right */
        .header-controls {
            display: flex;
            align-items: center;
            gap: 64px; /* Space between links and user icon */
        }

        .nav-links {
            display: flex;
            gap: 32px;
        }

        .nav-links a {
            color: #ffffff;
            text-decoration: none;
            font-size: 16px; /* Increased font size */
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

        .user-menu-container {
            position: relative;
            cursor: pointer;
        }

        .user-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            transition: all 0.2s;
        }

        /* Changed hover effect since circle is gone */
        .user-icon:hover {
            color: #60A5FA;
            transform: scale(1.05);
        }

        .user-icon svg {
            width: 36px; /* Increased icon size */
            height: 36px; /* Increased icon size */
        }

        .dropdown-menu {
            position: absolute;
            top: 50px; /* Adjusted slightly to match lack of circle padding */
            right: 0;
            width: 200px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 200;
        }

        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu a {
            display: block;
            padding: 16px 20px;
            color: #0B1E3D;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border-bottom: 1px solid #E2E8F0;
            transition: background 0.2s;
        }

        .dropdown-menu a:hover {
            background: #F0F4F8;
            color: #1B6FC8;
        }

        .dropdown-menu a.logout {
            color: #DC2626;
            border-bottom: none;
        }

        /*==========================
            MAIN REGISTRATION FORM
        ===========================*/
        .form-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative; 
            z-index: 1;
        }

        .background-icon {
            filter: blur(2px);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 1050px; /* Increased to 1050px per instruction */
            opacity: 0.10; 
            z-index: -1; 
            pointer-events: none; 
        }

        .register-container {
            width: 100%;
            max-width: 650px;
            padding: 40px;
        }

        .register-container h1 {
            color: #ffffff;
            font-size: 48px;
            font-weight: 400;
            text-align: center;
            margin-bottom: 48px;
        }

        .register-container h1 em {
            font-weight: 600;
            font-style: italic;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px 32px;
            margin-bottom: 40px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
        }

        .input-group label {
            color: #ffffff;
            font-size: 14px;
            font-weight: 200;
            margin-bottom: 8px;
        }

        .input-group input, .input-group select {
            width: 100%;
            height: 44px;
            background: #ffffff;
            border: none;
            border-radius: 2px;
            padding: 0 16px;
            font-size: 14px;
            color: #333333;
            font-family: 'Inter', sans-serif;
            outline: none;
        }

        .input-group input::placeholder, .input-group select {
            color: #A0A0A0;
            font-style: italic;
            font-weight: 300;
        }

        .submit-row {
            display: flex;
            justify-content: center;
            grid-column: 1 / -1;
            margin-top: 128px;
        }

        .register-btn {
            background: #ffffff;
            color: #0B1E3D;
            font-size: 24px;
            font-weight: 700;
            padding: 12px 40px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.25);
            background: #F0F4F8;
        }

    </style>
</head>

<body>

    <div id="splash">
        <div class="circle"></div>
        <div class="brand">
            <img src="{{ asset('images/Nexora_Logo_Transparent.png') }}" class="logo" alt="Logo">
            <img src="{{ asset('images/Banner Name White.png') }}" class="banner" alt="Banner">
        </div>
    </div>

    <div class="app-container">
        
        <header class="header">
            <a href="{{ route('dashboard') }}" class="nexora-logo" id="headerLogoBtn">
                <img src="{{ asset('images/Banner Transparent.png') }}" alt="Nexora Logo">
            </a>
            
            <div class="header-controls">
                <nav class="nav-links">
                    <a href="#" class="active">Registration</a>
                    <a href="#">User Management</a>
                    <a href="#">Service Desk</a>
                    <a href="#">Compliance Tracking</a>
                    <a href="#">Risk Management</a>
                </nav>

                <div class="user-menu-container" id="userMenuBtn">
                    <div class="user-icon">
                        <img src = "images/icon.png">   
                    </div>
                    
                    <div class="dropdown-menu" id="dropdownMenu">
                        <a href="#">My Profile</a>
                        <a href="#">System Settings</a>
                        <a href="{{ route('login') }}" class="logout" id="logoutBtn">Log Out</a>
                    </div>
                </div>
            </div>
        </header>
        
        <main class="form-section">
            
            <img src="{{ asset('images/nexora-icon.png') }}" class="background-icon" alt="">

            <div class="register-container">
                <h1>Register <em>a new</em> company</h1>
                
                <form action="#" method="POST">
                    @csrf
                    
                    <div class="form-grid">
                        <div class="input-group">
                            <label for="company_name">Company Name</label>
                            <input type="text" id="company_name" name="company_name" placeholder="Type here..">
                        </div>
                        
                        <div class="input-group">
                            <label for="industry">Industry</label>
                            <select id="industry" name="industry">
                                <option value="" disabled selected hidden>Please Select</option>
                                <option value="tech">Technology</option>
                                <option value="finance">Finance</option>
                                <option value="retail">Retail</option>
                                <option value="manufacturing">Manufacturing</option>
                            </select>
                        </div>
                        
                        <div class="input-group">
                            <label for="company_email">Company E-mail</label>
                            <input type="email" id="company_email" name="company_email" placeholder="sample@company.com">
                        </div>
                        
                        <div class="input-group">
                            <label for="phone_no">Phone No.</label>
                            <input type="text" id="phone_no" name="phone_no" placeholder="Type here..">
                        </div>
                        
                        <div class="input-group">
                            <label for="admin_name">Admin Name</label>
                            <input type="text" id="admin_name" name="admin_name" placeholder="Type here..">
                        </div>
                    </div>
                    
                    <div class="submit-row">
                        <button type="submit" class="register-btn">Register</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // --- 1. SPLASH SCREEN LOGIC ---
        const SPLASH_DURATION = 4300;
        const splash = document.getElementById("splash");

        setTimeout(() => {
            splash.style.opacity = "0";
            splash.style.pointerEvents = "none";
        }, SPLASH_DURATION);


        // --- 2. DROPDOWN TOGGLE LOGIC ---
        const userMenuBtn = document.getElementById('userMenuBtn');
        const dropdownMenu = document.getElementById('dropdownMenu');

        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation(); 
            dropdownMenu.classList.toggle('show');
        });

        window.addEventListener('click', function() {
            if (dropdownMenu.classList.contains('show')) {
                dropdownMenu.classList.remove('show');
            }
        });


        // --- 3. SMOOTH EXIT LOGIC ---
        function smoothExit(e, url) {
            e.preventDefault(); 
            const fader = document.createElement('div');
            fader.style.position = 'fixed';
            fader.style.inset = '0';
            fader.style.background = 'white';
            fader.style.opacity = '0';
            fader.style.transition = 'opacity 0.4s ease';
            fader.style.zIndex = '999999';
            document.body.appendChild(fader);

            void fader.offsetWidth;
            fader.style.opacity = '1';

            setTimeout(() => {
                window.location.href = url;
            }, 400); 
        }

        const logoutBtn = document.getElementById("logoutBtn");
        const headerLogoBtn = document.getElementById("headerLogoBtn");

        if (headerLogoBtn) headerLogoBtn.addEventListener("click", (e) => smoothExit(e, "{{ route('dashboard') }}"));
        if (logoutBtn) logoutBtn.addEventListener("click", (e) => smoothExit(e, "{{ route('login') }}"));
    </script>

</body>
</html>