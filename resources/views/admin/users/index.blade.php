@extends('layouts.admin')

@section('styles')
<style>
.users-card{
 background:#fff;
 border-radius:14px;
 overflow:hidden;
 box-shadow:0 8px 25px rgba(0,0,0,.05);
}

.users-table{
 width:100%;
 border-collapse:collapse;
 font-size:13px;
}

.users-table thead{
 background:#f8fafc;
}

.users-table th{
 padding:18px;
 text-align:left;
 color:#94a3b8;
 font-size:11px;
 text-transform:uppercase;
 letter-spacing:.7px;
}

.users-table td{
 padding:22px 18px;
 border-top:1px solid #eef2f7;
 color:#0f172a;
}

.users-table tr:hover{
 background:#f9fafb;
}

.badge{
 display:inline-block;
 padding:6px 12px;
 border-radius:7px;
 font-size:11px;
 font-weight:700;
}

.badge.success{
 background:#dcfce7;
 color:#16a34a;
}

.badge.muted{
 background:#e5e7eb;
 color:#64748b;
}

.badge.primary{
 background:#dbeafe;
 color:#2563eb;
}

.badge.danger{
 background:#fee2e2;
 color:#dc2626;
}

.btn-delete{
 border:0;
 background:#fee2e2;
 color:#dc2626;
 padding:8px 12px;
 border-radius:7px;
 font-size:12px;
 font-weight:700;
 cursor:pointer;
 transition:all .2s ease;
}

.btn-delete:hover{
 background:#dc2626;
 color:#fff;
}

.btn-delete:disabled{
 background:#f1f5f9;
 color:#94a3b8;
 cursor:not-allowed;
}
</style>
@endsection

@section('content')
<div class="container">
 <h3>Daftar Pengguna</h3>

 <div class="users-card">
 <table class="users-table">
 <thead>
 <tr>
 <th>No</th>
 <th>Pengguna</th>
 <th>Email</th>
 <th>Role</th>
 <th>Status</th>
 <th>Tanggal Daftar</th>
 <th>Aksi</th>
 </tr>
 </thead>
 <tbody>
 @forelse ($users as $user)
 <tr>
 <td>#{{ $loop->iteration }}</td>

 <td>
 <strong>{{ $user->name }}</strong>
 </td>

 <td>{{ $user->email }}</td>

 <td>
 @if($user->role == 'admin')
 <span class="badge danger">Admin</span>
 @else
 <span class="badge primary">Customer</span>
 @endif
 </td>

 <td>
 @if($user->isOnline())
 <span class="badge success">Online</span>
 @else
 <span class="badge muted">Offline</span>
 @endif
 </td>

 <td>{{ $user->created_at->diffForHumans() }}</td>

 <td>
 @if($user->id === auth()->id())
 <button type="button" class="btn-delete" disabled>Akun Anda</button>
 @else
 <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
 onsubmit="return confirm('Hapus pengguna {{ $user->name }}? Tindakan ini tidak bisa dibatalkan.')">
 @csrf
 @method('DELETE')
 <button type="submit" class="btn-delete">Hapus</button>
 </form>
 @endif
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="7" class="text-center" style="padding: 2rem;">Belum ada pengguna</td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>

 {{ $users->links() }}
</div>
@endsection
