<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>مدیریت کاربران</title>
  <style>
    body {
      font-family: "Vazirmatn", sans-serif;
      background-color: #121212;
      color: #e0e0e0;
      display: flex;
      min-height: 100vh;
      margin: 0;
    }

    .sidebar {
      width: 240px;
      background-color: #1f1f1f;
      padding: 20px;
      display: flex;
      flex-direction: column;
    }
    .sidebar a {
      text-decoration: none;
      color: #e0e0e0;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 8px;
      transition: 0.3s;
    }
    .sidebar a:hover { background-color: #333; }

    .main-content {
      flex: 1;
      padding: 25px;
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    th, td {
      text-align: center;
      padding: 12px;
      border-bottom: 1px solid #333;
    }
    th {
      background-color: #212121;
    }

    .btn {
      padding: 6px 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      color: white;
      font-size: 13px;
    }
    .btn-role { background-color: #2979ff; }
    .btn-delete { background-color: #e53935; }
    .btn-access { background-color: #43a047; }
    .btn-access.blocked { background-color: #fbc02d; }

    @media (max-width: 768px) {
      body { flex-direction: column; }
      .sidebar { width: 100%; flex-direction: row; justify-content: space-around; }
    }
  </style>
</head>

<body>
  <div class="sidebar">
    <a href="#">داشبورد</a>
    <a href="#">کاربران</a>
    <a href="#">تنظیمات</a>
  </div>

  <div class="main-content">
    <h2>لیست کاربران</h2>

    <table>
      <thead>
        <tr>
          <th>نام کاربری</th>
          <th>نام</th>
          <th>نقش</th>
          <th>دسترسی</th>
          <th>عملیات</th>
        </tr>
      </thead>
      <tbody id="userTable">
            @php $i = 0; @endphp
        @foreach($users as $user)
          <tr data-user-id="{{ $user->user_id }}">
            <td>{{ $user->user_name }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->is_admin }}</td>
            <td>{{ $user->is_active ? 'مجاز' : 'غیرمجاز' }}</td>
            <td>
              <button class="btn btn-role">تغییر نقش</button>
              <button class="btn btn-access {{ $user->is_active ? '' : 'blocked' }}">
                {{ $user->is_active ? 'غیرفعال کردن' : 'فعال کردن' }}
              </button>
              <button class="btn btn-delete">حذف</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <script>
    const csrf = '{{ csrf_token() }}';

    async function sendRequest(url, method, data = {}) {
      try {
        const res = await fetch(url, {
          method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
          },
          body: JSON.stringify(data)
        });
        const result = await res.json();
        alert(result.message || 'عملیات انجام شد');
      } catch (err) {
        console.error(err);
        alert('خطایی رخ داد');
      }
    }

    document.querySelectorAll('.btn-role').forEach(btn=>{
      btn.addEventListener('click', async e=>{
        const id = e.target.closest('tr').dataset.userId;
        await sendRequest(`{{ url('/users') }}/${id}/role`, 'PUT');
        location.reload();
      });
    });

    document.querySelectorAll('.btn-access').forEach(btn=>{
      btn.addEventListener('click', async e=>{
        const tr = e.target.closest('tr');
        const id = tr.dataset.userId;
        await sendRequest(`{{ url('/users') }}/${id}/access`, 'PUT');
        location.reload();
      });
    });

    document.querySelectorAll('.btn-delete').forEach(btn=>{
      btn.addEventListener('click', async e=>{
        const tr = e.target.closest('tr');
        const id = tr.dataset.userId;
        if(confirm('آیا از حذف کاربر مطمئن هستید؟')) {
          await sendRequest(`{{ url('/users') }}/${id}`, 'DELETE');
          tr.remove();
        }
      });
    });
  </script>
</body>
</html>



<!-- مسیرها (routes/web.php)
use App\Http\Controllers\UserController;

Route::put('/users/{id}/role', [UserController::class, 'changeRole'])->name('users.changeRole');
Route::put('/users/{id}/access', [UserController::class, 'toggleAccess'])->name('users.toggleAccess');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
 
-->

<!-- کنترلر (app/Http/Controllers/UserController.php)

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function changeRole($id)
    {
        $user = User::findOrFail($id);
        $user->role = $user->role === 'admin' ? 'user' : 'admin';
        $user->save();

        return response()->json(['success' => true, 'message' => "نقش کاربر تغییر یافت"]);
    }

    public function toggleAccess($id)
    {
        $user = User::findOrFail($id);
        $user->access = !$user->access;
        $user->save();

        $status = $user->access ? 'فعال شد' : 'غیرفعال شد';
        return response()->json(['success' => true, 'message' => "دسترسی کاربر $status"]);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'کاربر حذف شد']);
    }
}

 -->

