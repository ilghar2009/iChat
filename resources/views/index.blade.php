<!DOCTYPE html>
<html lang="fa">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
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

    <body data-user-id="{{ auth()->user()->user_id ?? 0 }}">

        <div class="sidebar" id="sidebar">
            <h2>Menu</h2>
            <div class="menu-btn" id="menu-b" onclick="toggleMenu()" style="padding: 10px;">☰</div>

            <a class="side" href="#">
                <div class="menu-item">Home</div>
            </a>

            <a class="side" href="#">
                <div class="menu-item">@if(auth()->user()) Profile @else Sign @endif</div>
            </a>

            @if(auth()->user()->is_admin)
                <a class="side" href="#">
                    <div class="menu-item">Clear Message</div>
                </a>
            @endif

        </div>

        <div class="main">

            <div class="header">
                <div class="menu-btn" onclick="toggleMenu()">☰</div>
                <h3>Chat Room</h3>
            </div>

            <div class="chat-area" id="chatArea">
                <!-- پیام ها اینجا نمایش داده میشن -->

                @foreach($messages  as $message)
                    <div class="message-container other-message-container">
                        <img alt="Profile Pic" class="profile-pic">
                        <div class="message @php if($message->user->user_id == auth()->user()->user_id) echo 'other-message'; else echo 'my-message';  @endphp">
                            <div class="message-sender">{{$message->user->name}}</div>
                            {{$message->body}}
                        </div>
                    </div>
                @endforeach

{{--                <div class="message-container my-message-container">--}}
{{--                    <img src="https://via.placeholder.com/40/2a2a2a/ffffff?text=You" alt="Profile Pic" class="profile-pic">--}}
{{--                    <div class="message my-message">--}}
{{--                        <div class="message-sender">You</div>--}}
{{--                        سلام! خوبم، ممنون. تو چطوری؟--}}
{{--                    </div>--}}
{{--                </div>--}}

            </div>

            <div class="input-area">
                <form action="#" method="get">
                    <input type="text" id="messageInput" name="body" placeholder="پیام خود را بنویسید...">
                    <!-- <input type="hidden" id="targetInput" style="display: none;"> -->
                    <button onclick="sendMessage()">ارسال</button>
                </form>
            </div>
        </div>

        <script>
            // ===== متغیرهای اصلی =====
            // دریافت ID کاربر از لاراول
            const currentUserId = {{ auth()->user()->user_id ?? 0 }};

            const chatArea = document.getElementById("chatArea");
            const messageInput = document.getElementById("messageInput");
            let lastMessageId = 0; // آخرین پیام دریافتی

            // ===== منو =====
            function toggleMenu() {
                document.getElementById("sidebar").classList.toggle("active");
            }

            // ===== تابع اضافه کردن پیام به صفحه =====
            function addMessage(senderId, senderName, messageContent, messageClass) {
                const messageContainer = document.createElement("div");
                messageContainer.className = `message-container ${messageClass}-container`;

                // ساخت عکس پروفایل (از سرویس ui-avatars برای تولید عکس بر اساس نام)
                const profilePic = document.createElement("img");
                // اگر نام خالی بود، "U" نشان بده
                const nameForAvatar = senderName || "User";
                profilePic.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(nameForAvatar)}&background=random&color=fff`;
                profilePic.alt = "Profile Pic";
                profilePic.className = "profile-pic";

                // ساخت پیام
                const messageDiv = document.createElement("div");
                messageDiv.className = `message ${messageClass}`;

                const senderInfo = document.createElement("div");
                senderInfo.className = "message-sender";
                senderInfo.textContent = senderName;

                messageDiv.appendChild(senderInfo);
                messageDiv.appendChild(document.createTextNode(messageContent));

                // اضافه کردن به کانتینر
                messageContainer.appendChild(profilePic);
                messageContainer.appendChild(messageDiv);

                // اضافه کردن به صفحه
                chatArea.appendChild(messageContainer);

                // اسکرول به پایین
                chatArea.scrollTop = chatArea.scrollHeight;
            }

            // ===== ارسال پیام به سرور (AJAX) =====
            async function sendMessage() {
                const messageText = messageInput.value.trim();
                if (messageText === "") return;

                // نمایش فوری پیام خودمان (Optimistic UI)
                // چون پیام خودمان است، استایل my-message می‌گیرد
                addMessage(currentUserId, "شما", messageText, "my-message");

                messageInput.value = ""; // پاک کردن اینپوت

                try {
                    const response = await fetch('/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ body: messageText })
                    });

                    const data = await response.json();

                    // اگر سرور موفق بود، lastMessageId را آپدیت کن
                    if (data.message && data.message.id) {
                        lastMessageId = data.message.id;
                    }
                } catch (error) {
                    console.error('خطا در ارسال پیام:', error);
                    alert('خطا در ارسال پیام!');
                }
            }

            // ===== اتصال SSE (گوش دادن به پیام‌های جدید) =====
            function initSSE() {
                // اتصال به روت stream و فرستادن آخرین ID
                const source = new EventSource(`/chat/stream?last_id=${lastMessageId}`);

                source.onmessage = function(event) {
                    try {
                        const data = JSON.parse(event.data);

                        // اگر ID پیام جدیدتر از آخرین پیامی بود که داشتیم
                        if (data.id > lastMessageId) {
                            lastMessageId = data.id;

                            // اگر پیام متعلق به خودمان نبود، نمایش بده
                            // (چون پیام خودمان را قبلاً با AJAX اضافه کردیم)
                            if (data.user_id != currentUserId) {
                                addMessage(data.user_id, data.user_name, data.body, "other-message");
                            }
                        }
                    } catch (e) {
                        console.error('خطا در پردازش داده SSE:', e);
                    }
                };

                source.onerror = function(event) {
                    console.error('خطا در اتصال SSE');
                    source.close();
                    // تلاش مجدد بعد از ۳ ثانیه
                    setTimeout(initSSE, 3000);
                };
            }

            // ===== رویدادها =====

            // کلیک روی دکمه ارسال
            document.querySelector('.input-area button').addEventListener('click', function(e) {
                e.preventDefault(); // جلوگیری از رفرش فرم
                sendMessage();
            });

            // ارسال با کلید Enter
            messageInput.addEventListener("keypress", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault(); // جلوگیری از رفرش فرم
                    sendMessage();
                }
            });

            // اسکرول به پایین در بارگذاری اولیه
            chatArea.scrollTop = chatArea.scrollHeight;

            // ===== شروع SSE =====
            initSSE();
        </script>

    </body>
</html>