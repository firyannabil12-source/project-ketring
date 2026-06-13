# Activity Diagram Web Risha Catering

Diagram ini dibuat lebih sederhana agar mudah dimasukkan ke draw.io / PlantUML dan tetap menggambarkan alur utama sistem.

## Activity Diagram Pemesanan Katering

Copy mulai dari `@startuml` sampai `@enduml`, lalu paste ke draw.io melalui **Arrange > Insert > Advanced > PlantUML**.

```plantuml
@startuml
title Activity Diagram Pemesanan Katering Mama Iksan

skinparam backgroundColor white
skinparam shadowing false
skinparam activity {
  BackgroundColor white
  BorderColor #333333
  FontColor #111111
  DiamondBackgroundColor white
  DiamondBorderColor #333333
  StartColor black
  EndColor black
}
skinparam ArrowColor #333333
skinparam ArrowThickness 1.2
skinparam swimlane {
  BorderColor #333333
  TitleBackgroundColor #f5f5f5
  TitleFontColor #111111
}

|Pembeli|
start
:Membuka website;
:Login / daftar akun;

|Sistem|
:Validasi login;
if (Login valid?) then (Ya)
  :Menampilkan halaman utama;
else (Tidak)
  :Menampilkan pesan login gagal;
  |Pembeli|
  :Mengisi ulang data login;
  |Sistem|
  :Validasi login;
endif

|Pembeli|
:Mencari dan melihat menu;

|Sistem|
:Menampilkan daftar menu;

|Pembeli|
:Memilih menu;
:Menambahkan menu ke keranjang;

|Sistem|
:Validasi stok menu;
if (Stok tersedia?) then (Ya)
  :Menyimpan item ke session keranjang;
  :Menampilkan isi keranjang dan total harga;
else (Tidak)
  :Menampilkan notifikasi stok tidak cukup;
  |Pembeli|
  :Memilih menu / jumlah lain;
 stop
endif

|Pembeli|
:Checkout pesanan;
:Mengisi data pemesanan;
:Memilih pembayaran Duitku;
:Mengirim pesanan;

|Sistem|
:Validasi data checkout;
if (Data valid?) then (Ya)
  :Membuat pesanan;
  :Menyimpan detail pesanan;
  :Mengurangi stok menu;
  :Membuat invoice pembayaran Duitku;
else (Tidak)
  :Menampilkan pesan kesalahan;
  |Pembeli|
  :Memperbaiki data checkout;
  stop
endif

if (Invoice berhasil dibuat?) then (Ya)
  :Mengarahkan ke halaman pembayaran Duitku;
else (Tidak)
  :Menampilkan notifikasi gagal membuat pembayaran;
  |Pembeli|
  :Mengulang checkout / menghubungi admin;
  stop
endif

|Pembeli|
:Melakukan pembayaran;

|Sistem|
:Menerima callback pembayaran Duitku;
if (Pembayaran berhasil?) then (Ya)
  :Mengubah payment_status menjadi paid;
  :Mengubah status pesanan menjadi diproses;
else (Tidak)
  :Mengubah payment_status menjadi expired;
  :Menampilkan status pembayaran belum berhasil;
  |Pembeli|
  :Melakukan pembayaran ulang sebelum kedaluwarsa;
  stop
endif

|Admin / Penjual|
:Login admin;

|Sistem|
:Validasi akun admin;
if (Admin valid?) then (Ya)
  :Menampilkan dashboard admin;
else (Tidak)
  :Menampilkan pesan login admin gagal;
  stop
endif

|Admin / Penjual|
:Membuka pesanan masuk;
:Mengonfirmasi pembayaran bila diperlukan;
:Memperbarui status pesanan;

|Sistem|
:Menyimpan status pesanan;
:Mengirim status terbaru ke halaman riwayat;

|Pembeli|
:Melihat status pesanan;
:Mengunduh invoice;

|Admin / Penjual|
stop

@enduml
```

## ERD Diagram

Copy mulai dari `@startuml` sampai `@enduml`, lalu paste ke draw.io melalui **Arrange > Insert > Advanced > PlantUML**.

```plantuml
@startuml
title ERD Sistem Pemesanan Catering

left to right direction

skinparam backgroundColor white
skinparam shadowing false
skinparam rectangle {
 BackgroundColor #9caf96
 BorderColor #9caf96
 FontColor #1f2d1f
 FontStyle bold
}
skinparam usecase {
 BackgroundColor #28c3d4
 BorderColor #28c3d4
 FontColor black
}
skinparam ArrowColor #9b9b9b
skinparam ArrowThickness 1.5

rectangle "Users" as USERS
rectangle "Orders" as ORDERS
rectangle "Order Items" as ITEMS
rectangle "Menus" as MENUS

usecase "id_user" as U_ID
usecase "name" as U_NAME
usecase "email" as U_EMAIL
usecase "password" as U_PASS
usecase "role" as U_ROLE

usecase "id_order" as O_ID
usecase "user_id" as O_USER
usecase "customer_name" as O_NAME
usecase "customer_phone" as O_PHONE
usecase "event_date" as O_DATE
usecase "event_address" as O_ADDR
usecase "total_price" as O_TOTAL
usecase "status" as O_STATUS
usecase "payment_status" as O_PAY

usecase "id_item" as I_ID
usecase "order_id" as I_ORDER
usecase "menu_id" as I_MENU
usecase "quantity" as I_QTY
usecase "price" as I_PRICE

usecase "id_menu" as M_ID
usecase "name" as M_NAME
usecase "category" as M_CAT
usecase "price" as M_PRICE
usecase "stock" as M_STOCK

usecase "Membuat" as R1
usecase "Memiliki" as R2
usecase "Berisi" as R3

U_NAME - USERS
U_EMAIL - USERS
U_ID - USERS
U_PASS - USERS
U_ROLE - USERS

USERS -- R1
R1 -- ORDERS

O_ID - ORDERS
O_USER - ORDERS
O_NAME - ORDERS
O_PHONE - ORDERS
O_DATE - ORDERS
O_ADDR - ORDERS
O_TOTAL - ORDERS
O_STATUS - ORDERS
O_PAY - ORDERS

ORDERS -- R2
R2 -- ITEMS

I_ID - ITEMS
I_ORDER - ITEMS
I_MENU - ITEMS
I_QTY - ITEMS
I_PRICE - ITEMS

ITEMS -- R3
R3 -- MENUS

M_ID - MENUS
M_NAME - MENUS
M_CAT - MENUS
M_PRICE - MENUS
M_STOCK - MENUS

@enduml
```

## Activity Diagram Manajemen Menu Admin

```plantuml
@startuml
title Activity Diagram Manajemen Menu Admin

|Admin|
start
:Login admin;

|Sistem Web|
:Validasi akun admin;

if (Login valid?) then (Ya)
 |Admin|
 :Buka halaman stok/menu;
 :Pilih tambah, edit, atau hapus menu;
 :Mengisi data menu;

 |Sistem Web|
 :Validasi data menu;
 :Simpan perubahan menu;

 |Admin|
 :Melihat daftar menu terbaru;
 stop
else (Tidak)
 |Admin|
 :Tampilkan pesan login gagal;
 stop
endif

@enduml
```

## Swimlane

- **Pelanggan**: memilih menu, checkout, membayar, melihat status, dan mengunduh invoice.
- **Sistem Web**: memvalidasi data, menyimpan pesanan, membuat pembayaran, dan memperbarui status.
- **Duitku**: menangani halaman pembayaran dan mengirim status pembayaran.
- **Admin**: melihat pesanan, mengubah status pesanan, dan mengelola menu.

## Use Case Diagram

Copy mulai dari `@startuml` sampai `@enduml`, lalu paste ke draw.io melalui **Arrange > Insert > Advanced > PlantUML**.

```plantuml
@startuml
title Use Case Diagram Sistem Pemesanan Catering

left to right direction

skinparam backgroundColor white
skinparam shadowing false
skinparam packageStyle rectangle
skinparam actorStyle awesome
skinparam rectangle {
 BorderColor #00a884
 BackgroundColor white
 RoundCorner 18
}
skinparam usecase {
 BackgroundColor #00a884
 BorderColor #00a884
 FontColor white
 FontStyle bold
}
skinparam actor {
 BackgroundColor #00a884
 BorderColor black
 FontColor black
 FontStyle bold
}
skinparam ArrowColor #00a884
skinparam ArrowThickness 1.3

actor "User" as User
actor "Admin" as Admin

rectangle "Sistem Pemesanan Catering" {
 usecase "Register / Login" as U1
 usecase "Melihat Menu" as U2
 usecase "Membuat Pesanan" as U3
 usecase "Pilih Lokasi Acara" as U4
 usecase "Melakukan\nPembayaran" as U5
 usecase "Cek Status Pesanan" as U6

 usecase "Login Admin" as A1
 usecase "Kelola Menu\nCatering" as A2
 usecase "Melihat Pesanan" as A3
 usecase "Konfirmasi\nPembayaran" as A4
 usecase "Ubah Status\nPesanan" as A5
 usecase "Kelola Pengguna" as A6

 U1 -[hidden]down- U2
 U2 -[hidden]down- U3
 U3 -[hidden]down- U4
 U4 -[hidden]down- U5
 U5 -[hidden]down- U6

 A1 -[hidden]down- A2
 A2 -[hidden]down- A3
 A3 -[hidden]down- A4
 A4 -[hidden]down- A5
 A5 -[hidden]down- A6

 U1 -[hidden]right- A1
 U2 -[hidden]right- A2
 U3 -[hidden]right- A3
 U4 -[hidden]right- A4
 U5 -[hidden]right- A5
 U6 -[hidden]right- A6
}

User --> U1
User --> U2
User --> U3
User --> U4
User --> U5
User --> U6

Admin --> A1
Admin --> A2
Admin --> A3
Admin --> A4
Admin --> A5
Admin --> A6

@enduml
```
