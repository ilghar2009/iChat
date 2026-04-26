<!DOCTYPE html>
<html lang="fa">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>iChat</title>

        <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif;
        }

        body {
            background: #121212;
            color: #fff;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* ===== Sidebar ===== */
        .sidebar {
            width: 250px;
            background: #1e1e1e;
            padding: 20px;
            transition: 0.3s;
            flex-shrink: 0;
        }
        a{
            text-decoration: none;
            color:#fff;
        }

        .sidebar h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .menu-item {
            padding: 12px 15px;
            margin-bottom: 12px;
            background: #2a2a2a;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.2s;
            text-align: center;
        }

        .menu-item:hover {
            background: #3a3a3a;
        }

        /* ===== Main Content ===== */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            overflow: hidden;
        }

        .header {
            display: none;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background: #1e1e1e;
            padding: 15px;
            border-radius: 10px;
        }
        #menu-b{
            display: none;
        }
        .menu-btn {
            font-size: 24px;
            cursor: pointer;
        }

        /* ===== Chat Area ===== */
        .chat-area {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            background: #1e1e1e;
            border-radius: 12px;
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            gap: 15px; /* Increased gap for better spacing with profiles */
        }

        form{
            width: 100%;
        }

        .message-container {
            display: flex;
            align-items: center; /* برای اینکه پروفایل و پیام هم‌تراز باشن */
            max-width: 100%;
            gap: 10px; /* فاصله بین پروفایل و پیام */
        }

        .message-container.my-message-container {
            flex-direction: row-reverse; /* پیام های خودمون: عکس سمت راست */
        }

        .message {
            padding: 12px 18px;
            border-radius: 10px;
            max-width: 65%; /* کمی کوچکتر شد تا کنار پروفایل جا بشه */
            word-wrap: break-word;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .my-message {
            background: #007bff;
            border-bottom-right-radius: 2px;
        }

        .other-message {
            background: #2a2a2a;
            border-bottom-left-radius: 2px;
        }

        .message-sender {
            font-size: 0.8em;
            color: #ccc;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #555; /* رنگ پیش فرض */
            object-fit: cover; /* برای اینکه عکس خوب جا بشه */
            flex-shrink: 0; /* برای اینکه عکس فشرده نشه */
        }

        /* ===== Input Area ===== */
        .input-area {
            display: flex;
            padding: 15px;
            background: #1e1e1e;
            border-radius: 12px;
            cursor: pointer;
        }
        form{
            display: flex;
        }

        .input-area input {
            flex: 1;
            padding: 12px 15px;
            background: #2a2a2a;
            border: none;
            border-radius: 8px;
            color: #fff;
            margin-right: 10px;
            outline: none;
        }
        .input-area input::placeholder {
            color: #aaa;
        }

        .input-area button {
            padding: 12px 20px;
            background: #007bff;
            border: none;
            border-radius: 8px;
            color: #fff;
            cursor: pointer;
            transition: 0.2s;
            font-weight: bold;
        }

        .input-area button:hover {
            background: #0056b3;
        }


        /* ===== Mobile ===== */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                position: absolute;
                top: 0;
                left: -100%;
                height: 100%;
                z-index: 1000;
                background: #1e1e1e;
                box-shadow: 2px 0 5px rgba(0,0,0,0.5);
            }

            .sidebar.active {
                left: 0;
            }

            .main {
                padding: 15px;
                width: 100%;
            }

            .header {
                display: flex;
            }
            #menu-b{
                display:block;
            }

            .chat-area {
                height: calc(100vh - 150px);
            }

            .input-area {
                position: sticky;
                bottom: 15px;
                background: #121212;
                padding: 10px;
            }

            .input-area input {
                margin-right: 5px;
            }
            .input-area button {
                padding: 10px 15px;
            }

            /* تنظیمات موبایل برای پروفایل */
            .message-container.my-message-container {
                flex-direction: row-reverse;
            }
            .profile-pic {
                width: 35px;
                height: 35px;
            }
            .message {
                max-width: 75%; /* کمی بیشتر جا بده */
            }
        }
        </style>
    </head>

    <body>

        <div class="sidebar" id="sidebar">
            <h2>Menu</h2>
            <div class="menu-btn" id="menu-b" onclick="toggleMenu()" style="padding: 10px;">☰</div>

            <a class="side" href="#">
                <div class="menu-item">Home</div>
            </a>

            <a class="side" href="#">
                <div class="menu-item">Profile</div>
            </a>

            <a class="side" href="#">
                <div class="menu-item">Clear Message</div>
            </a>
        </div>

        <div class="main">

            <div class="header">
                <div class="menu-btn" onclick="toggleMenu()">☰</div>
                <h3>Chat Room</h3>
            </div>

            <div class="chat-area" id="chatArea">
                <!-- پیام ها اینجا نمایش داده میشن -->
                <div class="message-container other-message-container">
                    <img src="https://via.placeholder.com/40/007bff/ffffff?text=U1" alt="Profile Pic" class="profile-pic">
                    <div class="message other-message">
                        <div class="message-sender">User1</div>
                        سلام! چطوری؟
                    </div>
                </div>
                <div class="message-container my-message-container">
                    <img src="https://via.placeholder.com/40/2a2a2a/ffffff?text=You" alt="Profile Pic" class="profile-pic">
                    <div class="message my-message">
                        <div class="message-sender">You</div>
                        سلام! خوبم، ممنون. تو چطوری؟
                    </div>
                </div>
                <div class="message-container other-message-container">
                    <img src="https://via.placeholder.com/40/007bff/ffffff?text=U2" alt="Profile Pic" class="profile-pic">
                    <div class="message other-message">
                        <div class="message-sender">User2</div>
                        منم خوبم. آماده ای برای شروع؟
                    </div>
                </div>
            </div>

            <div class="input-area">
                <form action="#" method="get">
                    <input type="text" id="messageInput" placeholder="پیام خود را بنویسید...">
                    <!-- <input type="hidden" id="targetInput" style="display: none;"> -->
                    <button onclick="sendMessage()">ارسال</button>
                </form>
            </div>

        </div>

        <script>
            function toggleMenu() {
                document.getElementById("sidebar").classList.toggle("active");
            }

            const chatArea = document.getElementById("chatArea");
            const messageInput = document.getElementById("messageInput");

            function sendMessage() {
                const messageText = messageInput.value.trim();
                if (messageText === "") return;

                const senderName = "You"; // نام کاربری خودمون
                addMessage(senderName, messageText, "my-message"); // اضافه کردن پیام خودمون

                messageInput.value = ""; // پاک کردن اینپوت
                chatArea.scrollTop = chatArea.scrollHeight; // اسکرول به پایین
            }

            // تابع اضافه کردن پیام (هم برای خودمون هم برای دیگران)
            function addMessage(senderName, messageContent, messageClass) {
                // ایجاد کانتینر پیام و پروفایل
                const messageContainer = document.createElement("div");
                messageContainer.className = `message-container ${messageClass}-container`;

                // ایجاد خود پیام
                const messageDiv = document.createElement("div");
                messageDiv.className = `message ${messageClass}`;

                const senderInfo = document.createElement("div");
                senderInfo.className = "message-sender";
                senderInfo.textContent = senderName;

                messageDiv.appendChild(senderInfo);
                messageDiv.appendChild(document.createTextNode(messageContent));

                // اضافه کردن عکس و پیام به کانتینر
                messageContainer.appendChild(profilePic);
                messageContainer.appendChild(messageDiv);

                // اضافه کردن کانتینر پیام به چت اریا
                chatArea.appendChild(messageContainer);
                chatArea.scrollTop = chatArea.scrollHeight;
            }

            // ارسال پیام با کلید Enter
            messageInput.addEventListener("keypress", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    sendMessage();
                }
            });

            // اسکرول به پایین در بارگذاری اولیه صفحه
            chatArea.scrollTop = chatArea.scrollHeight;

            const input = document.getElementById('targetInput');

            // روی همه‌ی message-container ها گوش می‌ذاریم
            // document.querySelectorAll('.message-container').forEach(container => {
            //     container.addEventListener('dblclick', function () {
            //         const messageId = this.id; // id همون message-container
            //         if (messageId) {
            //         input.value = messageId;
            //         } else {
            //         console.warn('این پیام id نداره');
            //         }
            //     });
            // });

        </script>

    </body>
</html>