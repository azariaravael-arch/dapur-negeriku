@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
<br>

<h2 class="text-2xl font-bold text-[#5B6541] mb-6 ml-6 mobile:ml-0 mobile:text-lg mobile:mb-4">Pengguna</h2>

<!-- SISTEM NOTIFIKASI UNIFIED - SAMA SEPERTI BANNER (ATAS TENGAH) -->
<div id="notificationContainer" class="fixed top-20 inset-x-0 z-[9999] hidden flex justify-center">
    <div id="notificationContent"
        class="text-white p-3 rounded-lg shadow-lg flex items-center gap-3 animate-slide-down min-w-[300px] max-w-[400px] w-auto mobile:max-w-[80vw] mobile:min-w-[260px] mobile:p-2.5">
        <div id="notificationIconContainer" class="flex-shrink-0">
            <span id="notificationIcon" class="material-icons text-lg mobile:text-base"></span>
        </div>
        <div class="flex-1">
            <p id="notificationTitle"
                class="font-semibold text-sm mb-1 mobile:text-xs mobile:font-semibold mobile:mb-0.5"></p>
            <p id="notificationMessage" class="text-xs opacity-95 leading-tight mobile:text-xs mobile:leading-tight">
            </p>
        </div>
        <button onclick="closeNotification()"
            class="text-white/70 hover:text-white transition-colors ml-1 flex-shrink-0">
            <span class="material-icons text-sm mobile:text-xs">close</span>
        </button>
    </div>
</div>

<!-- POPUP KONFIRMASI HAPUS -->
<div id="deleteConfirmationOverlay"
    class="fixed inset-0 bg-black bg-opacity-50 z-[9998] hidden items-center justify-center p-4 mobile:p-2">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative overflow-hidden border border-[#5B6541] mobile:max-w-[90vw]">
        <form id="deleteForm" method="POST" class="p-6 mobile:p-4">
            @csrf
            @method('DELETE')

            <!-- Icon -->
            <div class="flex justify-center mb-4 mobile:mb-3">
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mobile:w-12 mobile:h-12">
                    <span class="material-icons text-red-600 text-3xl mobile:text-2xl">delete_forever</span>
                </div>
            </div>

            <!-- Title -->
            <div class="text-center mb-4 mobile:mb-3">
                <h3 class="text-xl font-bold text-[#5B6541] mobile:text-lg">Konfirmasi Hapus</h3>
            </div>

            <!-- Message -->
            <div class="text-center mb-6 mobile:mb-4">
                <p class="text-gray-600 mobile:text-sm" id="deleteMessage">
                    Data pengguna akan dihapus secara permanen
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center gap-4 mobile:gap-3">
                <button type="button" onclick="closeDeleteConfirmation()"
                    class="px-6 py-2.5 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium transition-colors mobile:px-4 mobile:py-2 mobile:text-sm">
                    Batal
                </button>
                <button type="submit"
                    class="px-6 py-2.5 bg-[#5B6541] text-white rounded-xl hover:bg-[#4a4f35] font-medium transition-colors mobile:px-4 mobile:py-2 mobile:text-sm">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Container utama untuk filter dan tombol - RESPONSIF TANPA PADDING -->
<div class="flex items-center justify-between mb-5 ml-6 mr-6 mobile:ml-0 mobile:mr-0 mobile:mb-3 mobile:px-0 mobile:gap-1 mobile:w-full mobile:justify-start mobile:overflow-x-auto mobile:no-scrollbar">
    <!-- Search - REAL-TIME SEARCH dengan clear button -->
    <div class="flex items-center gap-1 mobile:flex-shrink-0">
        <div class="relative group">
            <span class="material-icons absolute left-2 top-1.5 text-[#5B6541] text-sm mobile:text-xs mobile:left-1.5 mobile:top-1 pointer-events-none">search</span>
            <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
                class="pl-7 pr-8 py-1.5 border border-[#5B6541] text-[#5B6541] rounded-lg w-48 mobile:w-28 mobile:text-xs mobile:py-1 mobile:pl-6 mobile:pr-7 bg-[#F7F6F2] mobile:text-xs transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#5B6541]/30 focus:border-[#5B6541]"
                placeholder="cari..."
                data-base-url="{{ route('pengguna.index') }}">
            <!-- Clear button yang muncul jika ada teks -->
            <button id="clearSearch" class="absolute right-2 top-1.5 text-[#5B6541] opacity-0 invisible transition-all duration-200 hover:text-[#4a4f35] mobile:right-1.5 mobile:top-1">
                <span class="material-icons text-sm mobile:text-xs">close</span>
            </button>
            <button
                class="px-3 py-1.5 bg-[#5B6541] text-white rounded-lg mobile:px-2 mobile:py-1 mobile:text-xs whitespace-nowrap">Cari</button>
        </div>
        <!-- Loading spinner untuk search -->
        <div id="searchLoading" class="hidden ml-2">
            <div class="w-4 h-4 border-2 border-[#5B6541] border-t-transparent rounded-full animate-spin"></div>
        </div>
    </div>

    <!-- Status dan Tambah Button - INTERAKTIF -->
    <div class="flex items-center gap-2 mobile:flex-shrink-0 mobile:gap-1 mobile:ml-1">
        <!-- Dropdown Status dengan indikator aktif -->
        <div class="relative">
            <div id="statusDropdown" class="relative cursor-pointer group">
                <div class="px-2 py-1.5 border border-[#5B6541] rounded-lg bg-[#5B6541] text-white cursor-pointer mobile:px-1.5 mobile:py-1 mobile:text-xs whitespace-nowrap flex items-center gap-1 hover:bg-[#4a4f35] transition-colors">
                    <span class="material-icons text-xs mobile:text-xs">filter_list</span>
                    <span>Status</span>
                    <!-- Indikator jika filter aktif -->
                    @if(request('status'))
                    <span class="w-1.5 h-1.5 bg-yellow-400 rounded-full ml-1 animate-pulse"></span>
                    @endif
                </div>
                <!-- Dropdown menu -->
                <div class="absolute top-full left-0 mt-1 w-32 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-10 group-hover:block hover:block">
                    <form action="{{ route('pengguna.index') }}" method="GET" id="statusForm">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <button type="submit" name="status" value=""
                            class="w-full text-left px-3 py-2 hover:bg-gray-100 text-gray-700 flex items-center justify-between {{ !request('status') ? 'bg-gray-100 font-medium' : '' }}">
                            <span>Semua</span>
                            @if(!request('status'))<span class="material-icons text-[#5B6541] text-sm">check</span>@endif
                        </button>
                        <button type="submit" name="status" value="aktif"
                            class="w-full text-left px-3 py-2 hover:bg-gray-100 text-gray-700 flex items-center justify-between {{ request('status') == 'aktif' ? 'bg-gray-100 font-medium' : '' }}">
                            <span>Aktif</span>
                            @if(request('status') == 'aktif')<span class="material-icons text-[#5B6541] text-sm">check</span>@endif
                        </button>
                        <button type="submit" name="status" value="tidak"
                            class="w-full text-left px-3 py-2 hover:bg-gray-100 text-gray-700 flex items-center justify-between {{ request('status') == 'tidak' ? 'bg-gray-100 font-medium' : '' }}">
                            <span>Tidak</span>
                            @if(request('status') == 'tidak')<span class="material-icons text-[#5B6541] text-sm">check</span>@endif
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tambah Button dengan hover effect -->
        <button onclick="openPopup()"
                class="px-3 py-2 bg-[#5B6541] text-white rounded-lg flex items-center gap-0.5 mobile:px-2 mobile:py-1 hover:bg-[#4a4f35] transform hover:-translate-y-0.5 transition-all duration-200 active:scale-95">
            <span class="material-icons text-sm mobile:text-xs">add</span>
            <span class="mobile:hidden text-xs"></span>
        </button>
    </div>
</div>

<!-- PESAN KOSONG JIKA TIDAK ADA DATA -->
<div id="noResults" class="{{ $penggunas->count() > 0 ? 'hidden' : '' }} ml-6 mr-6 mobile:ml-4 mobile:mr-4 mb-4">
    <div class="bg-[#F7F6F2] border border-[#5B6541] shadow-lg rounded-lg p-8 text-center">
        <span class="material-icons text-[#5B6541] text-4xl mb-3">person_off</span>
        <h3 class="text-lg font-semibold text-[#5B6541] mb-2">Tidak ada pengguna ditemukan</h3>
        <p class="text-gray-600">
            @if(request('search'))
                Tidak ditemukan hasil untuk "<span class="font-medium">{{ request('search') }}</span>"
            @elseif(request('status'))
                Tidak ada pengguna dengan status "<span class="font-medium">{{ request('status') == 'aktif' ? 'Aktif' : 'Tidak' }}</span>"
            @else
                Belum ada data pengguna yang ditambahkan
            @endif
        </p>
        @if(request('search') || request('status'))
        <button onclick="clearAllFilters()"
                class="mt-4 px-4 py-2 bg-[#5B6541] text-white rounded-lg hover:bg-[#4a4f35] transition-colors inline-flex items-center gap-2">
            <span class="material-icons text-sm">refresh</span>
            <span>Tampilkan Semua</span>
        </button>
        @endif
    </div>
</div>

<!-- Table Container - DENGAN PADDING SEPERTI SEBELUMNYA -->
<div class="ml-6 mr-6 mobile:ml-4 mobile:mr-4 mobile:overflow-x-auto {{ $penggunas->count() == 0 ? 'hidden' : '' }}">
    <!-- Table - KEMBALI SEPERTI SEBELUMNYA -->
    <div class="bg-[#F7F6F2] border border-[#5B6541] shadow-lg rounded-lg overflow-hidden mobile:min-w-[600px]">
        <table class="w-full text-left">
            <thead class="bg-[#5B6541] text-white">
                <tr>
                    <th class="p-3 pl-6 mobile:p-2 mobile:pl-4 mobile:text-sm">Foto</th>
                    <th class="p-3 mobile:p-2 mobile:text-sm">Nama</th>
                    <th class="p-3 mobile:p-2 mobile:text-sm">Email</th>
                    <th class="p-3 mobile:p-2 mobile:text-sm">Kata sandi</th>
                    <th class="p-3 mobile:p-2 mobile:text-sm">Status</th>
                    <th class="p-3 pr-6 mobile:p-2 mobile:pr-4 mobile:text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#5B6541]">
                @foreach($penggunas as $pengguna)
                <tr class="hover:bg-[#eceadd]">
                    <!-- Foto Profil 73x73 - KEMBALI SEPERTI SEBELUMNYA -->
                    <td class="p-3 pl-6 mobile:p-2 mobile:pl-4">
                        @if($pengguna->foto_pengguna)
                        <img src="{{ asset('storage/'.$pengguna->foto_pengguna) }}"
                            class="w-[73px] h-[73px] rounded-full object-cover mobile:w-12 mobile:h-12">
                        @else
                        <img src="/images/user.png" class="w-[73px] h-[73px] rounded-full object-cover mobile:w-12 mobile:h-12">
                        @endif
                    </td>
                    <td class="p-3 text-[#5B6541] font-semibold mobile:p-2 mobile:text-sm">{{ $pengguna->nama_pengguna }}</td>
                    <td class="p-3 text-[#5B6541] mobile:p-2 mobile:text-sm">{{ $pengguna->email_pengguna }}</td>
                    <td class="p-3 text-[#5B6541] mobile:p-2 mobile:text-sm">{{ substr($pengguna->password_admin, 0, 8) }}***</td>

                    <!-- Badge Status - KEMBALI SEPERTI SEBELUMNYA -->
                    <td class="p-3 mobile:p-2">
                        @if($pengguna->status === 'aktif')
                        <span class="px-6 py-2 bg-[#5B6541] text-white text-sm rounded-md font-medium mobile:px-2 mobile:py-1 mobile:text-xs">Aktif</span>
                        @else
                        <span class="px-6 py-2 bg-[#5D2222] text-white text-sm rounded-md font-medium mobile:px-2 mobile:py-1 mobile:text-xs">Tidak</span>
                        @endif
                    </td>

                    <!-- Aksi - KEMBALI SEPERTI SEBELUMNYA -->
                    <td class="p-3 pr-6 mobile:p-2 mobile:pr-4">
                        <div class="flex gap-2 mobile:gap-1">
                            <button
                                onclick="openEditPopup('{{ $pengguna->id }}', '{{ $pengguna->nama_pengguna }}', '{{ $pengguna->email_pengguna }}', '{{ $pengguna->status }}', '{{ $pengguna->foto_pengguna ? asset('storage/'.$pengguna->foto_pengguna) : '' }}')"
                                class="px-3 py-1.5 border border-[#5B6541] text-[#5B6541] rounded-lg text-sm flex items-center gap-1 hover:bg-[#5B6541] hover:text-white transition-colors mobile:px-2 mobile:py-1 mobile:text-xs">
                                <span class="material-icons text-sm mobile:text-xs">edit</span>
                                <span class="mobile:hidden">Edit</span>
                            </button>
                            <button
                                onclick="openDeleteConfirmation('{{ $pengguna->id }}', '{{ $pengguna->nama_pengguna }}')"
                                class="px-3 py-1.5 border border-[#5B6541] text-[#5B6541] rounded-lg text-sm flex items-center gap-1 hover:bg-[#5B6541] hover:text-white transition-colors mobile:px-2 mobile:py-1 mobile:text-xs">
                                <span class="material-icons text-sm mobile:text-xs">delete</span>
                                <span class="mobile:hidden">Hapus</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- PAGINATION - DIPINDAH KE LUAR TABLE CONTAINER -->
<div class="mt-6 flex justify-center px-6 mobile:px-4 mobile:mt-4 {{ $penggunas->count() == 0 ? 'hidden' : '' }}">
    @if ($penggunas->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center space-x-1 mobile:space-x-0.5">
        {{-- Previous Page Link --}}
        @if (!$penggunas->onFirstPage())
        <a href="{{ $penggunas->previousPageUrl() }}"
            class="w-8 h-8 flex items-center justify-center text-[#5B6541] hover:text-[#4a4f35] hover:bg-gray-100 rounded transition-all duration-200 hover:scale-110 mobile:w-7 mobile:h-7">
            <span class="material-icons text-lg mobile:text-base">chevron_left</span>
        </a>
        @endif

        {{-- Pagination Elements --}}
        @php
        $current = $penggunas->currentPage();
        $last = $penggunas->lastPage();
        // Tampilkan maksimal 3 halaman
        $start = max(1, $current - 1);
        $end = min($last, $current + 1);

        // Selalu tampilkan halaman 1 jika tidak termasuk
        if ($start > 1 && $current > 2) {
        echo '<a href="' . $penggunas->url(1) . '"
            class="w-8 h-8 flex items-center justify-center text-[#5B6541] hover:text-[#4a4f35] hover:bg-gray-100 rounded transition-all duration-200 hover:scale-110 mobile:w-7 mobile:h-7 mobile:text-sm">1</a>';
        if ($start > 2) {
        echo '<span class="w-8 h-8 flex items-center justify-center text-gray-400 mobile:w-7 mobile:h-7">...</span>';
        }
        }

        for ($page = $start; $page <= $end; $page++) { if ($page==$current) {
            echo '<span class="w-8 h-8 flex items-center justify-center bg-[#5B6541] text-white rounded font-medium mobile:w-7 mobile:h-7 mobile:text-sm transform scale-110 transition-transform duration-200">'
            . $page . '</span>' ; } else { echo '<a href="' . $penggunas->url($page) . '" class="w-8 h-8 flex
            items-center justify-center text-[#5B6541] hover:text-[#4a4f35] hover:bg-gray-100 rounded transition-all duration-200 hover:scale-110 mobile:w-7 mobile:h-7 mobile:text-sm">' . $page . '</a>';
            }
            }

            // Selalu tampilkan halaman terakhir jika tidak termasuk
            if ($end < $last) { if ($end < $last - 1) {
                echo '<span class="w-8 h-8 flex items-center justify-center text-gray-400 mobile:w-7 mobile:h-7">...</span>' ; }
                echo '<a href="' . $penggunas->url($last) . '" class="w-8 h-8 flex items-center justify-center
                text-[#5B6541] hover:text-[#4a4f35] hover:bg-gray-100 rounded transition-all duration-200 hover:scale-110 mobile:w-7 mobile:h-7 mobile:text-sm">' . $last . '</a>';
                }
                @endphp

                {{-- Next Page Link --}}
                @if ($penggunas->hasMorePages())
                <a href="{{ $penggunas->nextPageUrl() }}"
                    class="w-8 h-8 flex items-center justify-center text-[#5B6541] hover:text-[#4a4f35] hover:bg-gray-100 rounded transition-all duration-200 hover:scale-110 mobile:w-7 mobile:h-7">
                    <span class="material-icons text-lg mobile:text-base">chevron_right</span>
                </a>
                @endif
    </nav>
    @endif
</div>

<!-- POPUP OVERLAY & FORM TAMBAH -->
<div id="popupOverlay" class="fixed inset-0 bg-black bg-opacity-30 z-50 hidden items-center justify-center p-4 mobile:p-2">
    <div class="bg-[#F7F6F2] rounded-2xl shadow-2xl w-full max-w-4xl relative overflow-hidden border border-[#5B6541] mobile:rounded-xl mobile:max-h-[90vh] mobile:overflow-y-auto mobile:w-[95vw]">
        <!-- Form Content -->
        <form id="addUserForm" action="{{ route('pengguna.store') }}" method="POST" enctype="multipart/form-data"
            class="p-8 mobile:p-4">
            @csrf
            <!-- Title dengan animasi -->
            <div class="mb-8 mobile:mb-6">
                <h3 class="text-2xl font-bold text-[#5B6541] mobile:text-xl flex items-center gap-2">
                    <span class="material-icons"></span>
                    Tambah Profil Pengguna
                </h3>
            </div>

            <!-- Error Messages -->
            @if($errors->any() && old('_token') && !old('_method'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg mobile:p-3 mobile:text-sm mobile:mb-4 animate-pulse">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Main Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mobile:gap-6">
                <!-- Left Column - Foto Upload -->
                <div class="space-y-8 mobile:space-y-6">
                    <!-- Foto Section -->
                    <div>
                        <h4 class="text-lg font-semibold text-[#5B6541] mb-4 mobile:text-base flex items-center gap-2">
                            <span class="material-icons text-base">photo_camera</span>
                            Foto
                        </h4>
                        <div class="space-y-4 mobile:space-y-3">
                            <!-- Upload Area dengan drag & drop -->
                            <div id="uploadArea" class="border-2 border-dashed border-[#5B6541] rounded-2xl p-8 text-center bg-white hover:bg-gray-50 cursor-pointer transition-all duration-300 hover:border-[#4a4f35] hover:shadow-lg mobile:p-4 mobile:rounded-xl"
                                onclick="document.getElementById('fotoInput').click()">
                                <div class="flex flex-col items-center justify-center space-y-4 mobile:space-y-3">
                                    <!-- Icon Area -->
                                    <div class="w-32 h-32 rounded-full flex items-center justify-center mb-2 mobile:w-20 mobile:h-20">
                                        <div class="relative w-full h-full">
                                            <span id="uploadIcon"
                                                class="material-icons text-gray-300 text-7xl mobile:text-5xl group-hover:text-[#5B6541] transition-colors">add_a_photo</span>
                                            <img id="imagePreviewThumbnail"
                                                class="absolute inset-0 w-full h-full object-cover rounded-full hidden animate-fadeIn">
                                        </div>
                                    </div>
                                    <!-- Upload Button -->
                                    <div class="mt-2">
                                        <button type="button"
                                            class="px-8 py-3 bg-[#5B6541] text-white rounded-xl hover:bg-[#4a4f35] font-medium text-base transition-all duration-200 hover:scale-105 active:scale-95 mobile:px-6 mobile:py-2 mobile:text-sm">
                                            <span class="material-icons text-sm mr-2">cloud_upload</span>
                                            Unggah Foto
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- File Input Hidden -->
                            <input type="file" id="fotoInput" name="foto_pengguna" accept="image/*" class="hidden"
                                onchange="previewImage(event)">
                            <!-- Description -->
                            <div class="text-center space-y-1 mobile:space-y-0.5">
                                <p class="text-sm text-gray-600 mobile:text-xs">Drag & drop atau klik untuk mengunggah foto</p>
                                <p class="text-xs text-gray-400">Format: JPG, PNG maks. 2MB</p>
                            </div>
                            @error('foto_pengguna')
                            <div class="text-red-600 text-sm mt-1 mobile:text-xs animate-pulse">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column - Form Fields dengan efek fokus -->
                <div class="space-y-8 mobile:space-y-6">
                    <!-- Nama -->
                    <div>
                        <label class="block text-lg font-semibold text-[#5B6541] mb-3 mobile:text-base flex items-center gap-2">
                            <span class="material-icons text-base">badge</span>
                            Nama
                        </label>
                        <div class="relative">
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-1 mobile:p-0.5 hover:border-[#5B6541] transition-colors duration-300">
                                <input type="text" name="nama_pengguna" value="{{ old('nama_pengguna') }}"
                                    class="w-full px-5 py-4 border-0 bg-transparent text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-0 mobile:px-4 mobile:py-3 mobile:text-sm transition-all duration-300"
                                    placeholder="Masukkan nama"
                                    onfocus="this.parentElement.style.borderColor = '#5B6541'"
                                    onblur="this.parentElement.style.borderColor = ''">
                            </div>
                        </div>
                        @error('nama_pengguna')
                        <div class="text-red-600 text-sm mt-2 mobile:text-xs animate-pulse">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-lg font-semibold text-[#5B6541] mb-3 mobile:text-base flex items-center gap-2">
                            <span class="material-icons text-base">email</span>
                            Email
                        </label>
                        <div class="relative">
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-1 mobile:p-0.5 hover:border-[#5B6541] transition-colors duration-300">
                                <input type="email" name="email_pengguna" value="{{ old('email_pengguna') }}"
                                    class="w-full px-5 py-4 border-0 bg-transparent text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-0 mobile:px-4 mobile:py-3 mobile:text-sm transition-all duration-300"
                                    placeholder="Masukkan email"
                                    onfocus="this.parentElement.style.borderColor = '#5B6541'"
                                    onblur="this.parentElement.style.borderColor = ''">
                            </div>
                        </div>
                        @error('email_pengguna')
                        <div class="text-red-600 text-sm mt-2 mobile:text-xs animate-pulse">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password dengan show/hide -->
                    <div>
                        <label class="block text-lg font-semibold text-[#5B6541] mb-3 mobile:text-base flex items-center gap-2">
                            <span class="material-icons text-base">lock</span>
                            Kata sandi
                        </label>
                        <div class="relative">
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-1 mobile:p-0.5 hover:border-[#5B6541] transition-colors duration-300">
                                <input type="password" name="password_admin" id="passwordInput"
                                    class="w-full px-5 py-4 border-0 bg-transparent text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-0 mobile:px-4 mobile:py-3 mobile:text-sm transition-all duration-300 pr-10"
                                    placeholder="Masukkan kata sandi"
                                    onfocus="this.parentElement.style.borderColor = '#5B6541'"
                                    onblur="this.parentElement.style.borderColor = ''">
                                <button type="button" onclick="togglePasswordVisibilityForm('passwordInput', this)"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#5B6541] transition-colors">
                                    <span class="material-icons text-sm">visibility_off</span>
                                </button>
                            </div>
                            <!-- Password strength indicator -->
                            <div id="passwordStrength" class="mt-2 hidden">
                                <div class="flex items-center gap-1">
                                    <div class="h-1 flex-1 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="strengthBar" class="h-full transition-all duration-300"></div>
                                    </div>
                                    <span id="strengthText" class="text-xs text-gray-500"></span>
                                </div>
                            </div>
                        </div>
                        @error('password_admin')
                        <div class="text-red-600 text-sm mt-2 mobile:text-xs animate-pulse">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- STATUS TOGGLE SEDERHANA (TANPA PERUBAHAN WARNA) -->
                    <div>
                        <label class="block text-lg font-semibold text-[#5B6541] mb-3 mobile:text-base flex items-center gap-2">
                            <span class="material-icons text-base">toggle_on</span>
                            Status
                        </label>
                        <div class="flex items-center space-x-4 mobile:space-x-3">
                            <!-- Toggle Container SEDERHANA tanpa perubahan warna -->
                            <div class="relative">
                                <input type="checkbox" id="statusToggle" class="sr-only peer" {{ old('status')=='aktif' ? 'checked' : '' }}>
                                <label for="statusToggle" class="cursor-pointer">
                                    <div class="toggle-container">
                                        <div class="toggle-circle"></div>
                                    </div>
                                </label>
                            </div>
                            <!-- Status Text dan Dot Indicator -->
                            <div class="flex items-center space-x-2 mobile:space-x-1.5">
                                <span class="text-gray-700 font-medium mobile:text-sm" id="statusText">{{ old('status', 'tidak') ==
                                    'aktif' ? 'Aktif' : 'Tidak Aktif' }}</span>
                                <span class="w-3 h-3 rounded-full {{ old('status', 'tidak') == 'aktif' ? 'bg-green-500' : 'bg-red-500' }} mobile:w-2.5 mobile:h-2.5 transition-colors duration-300"
                                    id="statusIndicator"></span>
                            </div>
                        </div>
                        <!-- HIDDEN INPUT UNTUK STATUS -->
                        <input type="hidden" name="status" id="statusValue" value="{{ old('status', 'tidak') }}">
                        @error('status')
                        <div class="text-red-600 text-sm mt-2 mobile:text-xs animate-pulse">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons dengan efek hover -->
            <div class="mt-12 pt-8 border-t border-dashed border-gray-300 mobile:mt-8 mobile:pt-6">
                <div class="flex justify-between items-center mobile:flex-col mobile:gap-3">
                    <!-- Batal di kiri -->
                    <button type="button" onclick="openCancelConfirmation('add')"
                        class="px-12 py-3.5 border-2 border-[#5B6541] text-[#5B6541] rounded-xl hover:bg-[#5B6541] hover:text-white font-medium text-base transition-all duration-200 hover:scale-105 active:scale-95 mobile:px-8 mobile:py-2.5 mobile:text-sm mobile:w-full flex items-center justify-center gap-2">
                        <span class="material-icons text-sm">close</span>
                        Batal
                    </button>
                    <!-- Simpan di kanan -->
                    <button type="submit"
                        class="px-12 py-3.5 bg-[#5B6541] text-white rounded-xl hover:bg-[#4a4f35] font-medium text-base transition-all duration-200 hover:scale-105 active:scale-95 mobile:px-8 mobile:py-2.5 mobile:text-sm mobile:w-full flex items-center justify-center gap-2">
                        <span class="material-icons text-sm">save</span>
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- POPUP OVERLAY & FORM EDIT -->
<div id="editPopupOverlay" class="fixed inset-0 bg-black bg-opacity-30 z-50 hidden items-center justify-center p-4 mobile:p-2">
    <div class="bg-[#F7F6F2] rounded-2xl shadow-2xl w-full max-w-4xl relative overflow-hidden border border-[#5B6541] mobile:rounded-xl mobile:max-h-[90vh] mobile:overflow-y-auto mobile:w-[95vw]">
        <!-- Form Edit -->
        <form id="editUserForm" method="POST" enctype="multipart/form-data" class="p-8 mobile:p-4">
            @csrf
            @method('PUT')
            <!-- Title -->
            <div class="mb-8 mobile:mb-6">
                <h3 class="text-2xl font-bold text-[#5B6541] mobile:text-xl flex items-center gap-2">

                    Edit Profil Pengguna
                </h3>
            </div>

            <!-- Main Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mobile:gap-6">
                <!-- Left Column - Foto Upload -->
                <div class="space-y-8 mobile:space-y-6">
                    <!-- Foto Section -->
                    <div>
                        <h4 class="text-lg font-semibold text-[#5B6541] mb-4 mobile:text-base flex items-center gap-2">
                            <span class="material-icons text-base"></span>
                            Foto
                        </h4>
                        <div class="space-y-4 mobile:space-y-3">
                            <!-- Upload Area -->
                            <div id="editUploadArea" class="border-2 border-dashed border-[#5B6541] rounded-2xl p-8 text-center bg-white hover:bg-gray-50 cursor-pointer transition-all duration-300 hover:border-[#4a4f35] mobile:p-4 mobile:rounded-xl"
                                onclick="document.getElementById('editFotoInput').click()">
                                <div class="flex flex-col items-center justify-center space-y-4 mobile:space-y-3">
                                    <!-- Icon Area atau Foto Saat Ini -->
                                    <div
                                        class="w-32 h-32 rounded-full flex items-center justify-center mb-2 overflow-hidden mobile:w-20 mobile:h-20">
                                        <div id="currentFotoContainer" class="relative w-full h-full">
                                            <!-- Foto akan ditampilkan di sini -->
                                        </div>
                                    </div>
                                    <!-- Upload Button -->
                                    <div class="mt-2">
                                        <button type="button"
                                            class="px-8 py-3 bg-[#5B6541] text-white rounded-xl hover:bg-[#4a4f35] font-medium text-base transition-all duration-200 hover:scale-105 mobile:px-6 mobile:py-2 mobile:text-sm">
                                            <span class="material-icons text-sm mr-2">cloud_upload</span>
                                            Ubah Foto
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- File Input Hidden -->
                            <input type="file" id="editFotoInput" name="foto_pengguna" accept="image/*" class="hidden"
                                onchange="previewEditImage(event)">
                            <!-- Description -->
                            <div class="text-center space-y-1 mobile:space-y-0.5">
                                <p class="text-sm text-gray-600 mobile:text-xs">Klik untuk mengubah foto profil</p>
                                <p class="text-xs text-gray-400">Format: JPG, PNG maks. 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Form Fields -->
                <div class="space-y-8 mobile:space-y-6">
                    <!-- Nama -->
                    <div>
                        <label class="block text-lg font-semibold text-[#5B6541] mb-3 mobile:text-base">Nama</label>
                        <div class="relative">
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-1 mobile:p-0.5 hover:border-[#5B6541] transition-colors duration-300">
                                <input type="text" name="nama_pengguna" id="editNama"
                                    class="w-full px-5 py-4 border-0 bg-transparent text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-0 mobile:px-4 mobile:py-3 mobile:text-sm"
                                    placeholder="Masukkan nama" required
                                    onfocus="this.parentElement.style.borderColor = '#5B6541'"
                                    onblur="this.parentElement.style.borderColor = ''">
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-lg font-semibold text-[#5B6541] mb-3 mobile:text-base">Email</label>
                        <div class="relative">
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-1 mobile:p-0.5 hover:border-[#5B6541] transition-colors duration-300">
                                <input type="email" name="email_pengguna" id="editEmail"
                                    class="w-full px-5 py-4 border-0 bg-transparent text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-0 mobile:px-4 mobile:py-3 mobile:text-sm"
                                    placeholder="Masukkan email" required
                                    onfocus="this.parentElement.style.borderColor = '#5B6541'"
                                    onblur="this.parentElement.style.borderColor = ''">
                            </div>
                        </div>
                    </div>

                    <!-- Password dengan show/hide -->
                    <div>
                        <label class="block text-lg font-semibold text-[#5B6541] mb-3 mobile:text-base">Kata sandi</label>
                        <div class="relative">
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-1 mobile:p-0.5 hover:border-[#5B6541] transition-colors duration-300">
                                <input type="password" name="password_admin" id="editPassword"
                                    class="w-full px-5 py-4 border-0 bg-transparent text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-0 mobile:px-4 mobile:py-3 mobile:text-sm pr-10"
                                    placeholder="Kosongkan jika tidak ingin mengubah"
                                    onfocus="this.parentElement.style.borderColor = '#5B6541'"
                                    onblur="this.parentElement.style.borderColor = ''">
                                <button type="button" onclick="togglePasswordVisibilityForm('editPassword', this)"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#5B6541] transition-colors">
                                    <span class="material-icons text-sm">visibility_off</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- STATUS TOGGLE SEDERHANA (TANPA PERUBAHAN WARNA) -->
                    <div>
                        <label class="block text-lg font-semibold text-[#5B6541] mb-3 mobile:text-base">Status</label>
                        <div class="flex items-center space-x-4 mobile:space-x-3">
                            <!-- Toggle Container SEDERHANA tanpa perubahan warna -->
                            <div class="relative">
                                <input type="checkbox" id="editStatusToggle" class="sr-only peer">
                                <label for="editStatusToggle" class="cursor-pointer">
                                    <div class="toggle-container">
                                        <div class="toggle-circle"></div>
                                    </div>
                                </label>
                            </div>
                            <!-- Status Text dan Dot Indicator -->
                            <div class="flex items-center space-x-2 mobile:space-x-1.5">
                                <span class="text-gray-700 font-medium mobile:text-sm" id="editStatusText">Tidak Aktif</span>
                                <span class="w-3 h-3 rounded-full bg-red-500 mobile:w-2.5 mobile:h-2.5 transition-colors duration-300" id="editStatusIndicator"></span>
                            </div>
                        </div>
                        <!-- HIDDEN INPUT UNTUK STATUS EDIT -->
                        <input type="hidden" name="status" id="editStatusValue" value="tidak">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-12 pt-8 border-t border-dashed border-gray-300 mobile:mt-8 mobile:pt-6">
                <div class="flex justify-between items-center mobile:flex-col mobile:gap-3">
                    <!-- Batal di kiri -->
                    <button type="button" onclick="openCancelConfirmation('edit')"
                        class="px-12 py-3.5 border-2 border-[#5B6541] text-[#5B6541] rounded-xl hover:bg-[#5B6541] hover:text-white font-medium text-base transition-all duration-200 hover:scale-105 active:scale-95 mobile:px-8 mobile:py-2.5 mobile:text-sm mobile:w-full flex items-center justify-center gap-2">
                        <span class="material-icons text-sm">close</span>
                        Batal
                    </button>
                    <!-- Update di kanan -->
                    <button type="submit"
                        class="px-12 py-3.5 bg-[#5B6541] text-white rounded-xl hover:bg-[#4a4f35] font-medium text-base transition-all duration-200 hover:scale-105 active:scale-95 mobile:px-8 mobile:py-2.5 mobile:text-sm mobile:w-full flex items-center justify-center gap-2">
                        <span class="material-icons text-sm">update</span>
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- POPUP KONFIRMASI BATAL -->
<div id="cancelConfirmationOverlay"
    class="fixed inset-0 bg-black bg-opacity-50 z-[9998] hidden items-center justify-center p-4 mobile:p-2">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative overflow-hidden border border-[#5B6541] mobile:max-w-[90vw]">
        <div class="p-6 mobile:p-4">
            <!-- Icon -->
            <div class="flex justify-center mb-4 mobile:mb-3">
                <div class="w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center mobile:w-12 mobile:h-12 animate-pulse">
                    <span class="material-icons text-yellow-600 text-3xl mobile:text-2xl">warning</span>
                </div>
            </div>

            <!-- Title -->
            <div class="text-center mb-4 mobile:mb-3">
                <h3 class="text-xl font-bold text-[#5B6541] mobile:text-lg">Apakah anda Yakin?</h3>
            </div>

            <!-- Message -->
            <div class="text-center mb-6 mobile:mb-4">
                <p class="text-gray-600 mobile:text-sm" id="cancelMessage">
                    Seluruh perubahan data pengguna tidak akan tersimpan
                </p>
                <p class="text-sm text-gray-500 mt-2"></p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center gap-4 mobile:gap-3">
                <button type="button" onclick="closeCancelConfirmation()"
                    class="px-6 py-2.5 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium transition-all duration-200 hover:scale-105 active:scale-95 mobile:px-4 mobile:py-2 mobile:text-sm">
                    Lanjut Edit
                </button>
                <button type="button" onclick="confirmCancel()"
                    class="px-6 py-2.5 bg-[#5B6541] text-white rounded-xl hover:bg-[#4a4f35] font-medium transition-all duration-200 hover:scale-105 active:scale-95 mobile:px-4 mobile:py-2 mobile:text-sm">
                    Ya, Batalkan
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animasi baru */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideIn {
        from { transform: translateX(-10px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }

    .animate-slideIn {
        animation: slideIn 0.3s ease-out;
    }

    .animate-pulse {
        animation: pulse 1.5s ease-in-out infinite;
    }

    /* Animasi untuk notifikasi slide down - SAMA SEPERTI BANNER */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-down {
        animation: slideDown 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    /* TOGGLE SEDERHANA - TANPA PERUBAHAN WARNA */
    .toggle-container {
        width: 64px;
        height: 32px;
        background-color: #e5e7eb !important;
        border-radius: 9999px;
        position: relative;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .toggle-circle {
        position: absolute;
        top: 4px;
        left: 4px;
        width: 24px;
        height: 24px;
        background-color: white;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    /* Efek hover ringan */
    #statusToggle + label .toggle-container:hover,
    #editStatusToggle + label .toggle-container:hover {
        background-color: #d1d5db !important;
    }

    /* Posisi circle saat checked */
    #statusToggle:checked + label .toggle-container .toggle-circle,
    #editStatusToggle:checked + label .toggle-container .toggle-circle {
        transform: translateX(32px) !important;
    }

    /* Nonaktifkan semua perubahan warna pada toggle */
    #statusToggle:checked + label .toggle-container,
    #editStatusToggle:checked + label .toggle-container,
    #statusToggle + label .toggle-container,
    #editStatusToggle + label .toggle-container {
        background-color: #e5e7eb !important;
    }

    #statusToggle:checked + label .toggle-container:hover,
    #editStatusToggle:checked + label .toggle-container:hover,
    #statusToggle + label .toggle-container:hover,
    #editStatusToggle + label .toggle-container:hover {
        background-color: #d1d5db !important;
    }

    /* Style untuk notifikasi persegi panjang seperti awal - SAMA SEPERTI BANNER */
    #notificationContent {
        padding: 0.75rem;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        border-radius: 0.375rem;
        background: linear-gradient(135deg, #5B6541 0%, #4a4f35 100%);
    }

    /* Responsive Media Queries untuk layar 300x800 */
    @media (max-width: 768px) {
        .toggle-container {
            width: 56px;
            height: 28px;
        }

        .toggle-circle {
            width: 20px;
            height: 20px;
            top: 4px;
            left: 4px;
        }

        #statusToggle:checked + label .toggle-container .toggle-circle,
        #editStatusToggle:checked + label .toggle-container .toggle-circle {
            transform: translateX(28px) !important;
        }

        /* Untuk search, status, tambah button - TANPA PADDING */
        .mobile\:ml-0 { margin-left: 0 !important; }
        .mobile\:mr-0 { margin-right: 0 !important; }
        .mobile\:mb-3 { margin-bottom: 0.75rem !important; }
        .mobile\:px-0 { padding-left: 0 !important; padding-right: 0 !important; }
        .mobile\:gap-1 { gap: 0.25rem !important; }

        /* Untuk tabel - DENGAN PADDING SEPERTI SEBELUMNYA */
        .mobile\:ml-4 { margin-left: 1rem !important; }
        .mobile\:mr-4 { margin-right: 1rem !important; }
        .mobile\:p-2 { padding: 0.5rem !important; }
        .mobile\:pl-4 { padding-left: 1rem !important; }
        .mobile\:pr-4 { padding-right: 1rem !important; }

        /* Untuk pagination */
        .mobile\:px-4 { padding-left: 1rem !important; padding-right: 1rem !important; }

        /* Ukuran font */
        .mobile\:text-xs { font-size: 0.75rem !important; }
        .mobile\:text-sm { font-size: 0.875rem !important; }
        .mobile\:text-base { font-size: 1rem !important; }
        .mobile\:text-lg { font-size: 1.125rem !important; }
        .mobile\:text-xl { font-size: 1.25rem !important; }

        /* Ukuran elemen */
        .mobile\:w-12 { width: 3rem !important; }
        .mobile\:h-12 { height: 3rem !important; }
        .mobile\:w-20 { width: 5rem !important; }
        .mobile\:h-20 { height: 5rem !important; }
        .mobile\:w-7 { width: 1.75rem !important; }
        .mobile\:h-7 { height: 1.75rem !important; }
        .mobile\:w-2\.5 { width: 0.625rem !important; }
        .mobile\:h-2\.5 { height: 0.625rem !important; }

        /* Padding button */
        .mobile\:px-2 { padding-left: 0.5rem !important; padding-right: 0.5rem !important; }
        .mobile\:py-1 { padding-top: 0.25rem !important; padding-bottom: 0.25rem !important; }
        .mobile\:px-1\.5 { padding-left: 0.375rem !important; padding-right: 0.375rem !important; }
        .mobile\:py-1\.5 { padding-top: 0.375rem !important; padding-bottom: 0.375rem !important; }

        /* Untuk search box */
        .mobile\:w-28 { width: 7rem !important; }
        .mobile\:pl-6 { padding-left: 1.5rem !important; }

        /* Layout */
        .mobile\:hidden { display: none !important; }
        .mobile\:flex-col { flex-direction: column !important; }
        .mobile\:w-full { width: 100% !important; }
        .mobile\:overflow-x-auto { overflow-x: auto !important; }
        .mobile\:flex-nowrap { flex-wrap: nowrap !important; }
        .mobile\:flex-shrink-0 { flex-shrink: 0 !important; }
        .mobile\:min-w-\[600px\] { min-width: 600px !important; }
        .mobile\:max-w-\[90vw\] { max-width: 90vw !important; }
        .mobile\:max-w-\[95vw\] { max-width: 95vw !important; }
        .mobile\:w-\[95vw\] { width: 95vw !important; }
        .mobile\:rounded-xl { border-radius: 0.75rem !important; }
        .mobile\:gap-6 { gap: 1.5rem !important; }
        .mobile\:space-y-6 > * + * { margin-top: 1.5rem !important; }
        .mobile\:space-x-3 > * + * { margin-left: 0.75rem !important; }
        .mobile\:space-x-1\.5 > * + * { margin-left: 0.375rem !important; }
        .mobile\:p-0\.5 { padding: 0.125rem !important; }
        .mobile\:px-4 { padding-left: 1rem !important; padding-right: 1rem !important; }
        .mobile\:py-3 { padding-top: 0.75rem !important; padding-bottom: 0.75rem !important; }
        .mobile\:px-8 { padding-left: 2rem !important; padding-right: 2rem !important; }
        .mobile\:py-2\.5 { padding-top: 0.625rem !important; padding-bottom: 0.625rem !important; }
        .mobile\:px-6 { padding-left: 1.5rem !important; padding-right: 1.5rem !important; }
        .mobile\:py-2 { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }
        .mobile\:text-5xl { font-size: 3rem !important; }
        .mobile\:text-2xl { font-size: 1.5rem !important; }
        .mobile\:max-h-\[90vh\] { max-height: 90vh !important; }
        .mobile\:overflow-y-auto { overflow-y: auto !important; }
        .mobile\:no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .mobile\:no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .mobile\:ml-1 { margin-left: 0.25rem !important; }
        .whitespace-nowrap { white-space: nowrap; }

        /* Untuk notifikasi mobile - SAMA SEPERTI BANNER */
        .mobile\:p-2\.5 {
            padding: 0.625rem !important;
        }

        .mobile\:max-w-\[80vw\] {
            max-width: 80vw !important;
        }

        .mobile\:min-w-\[260px\] {
            min-width: 260px !important;
        }

        .mobile\:top-16 {
            top: 4rem !important;
        }
    }

    /* Responsive untuk tablet (768px - 1024px) */
    @media (min-width: 768px) and (max-width: 1024px) {
        /* Container utama */
        .tablet\:px-8 {
            padding-left: 2rem !important;
            padding-right: 2rem !important;
        }

        /* Search box lebih besar di tablet */
        .tablet\:w-64 {
            width: 16rem !important;
        }

        /* Tombol lebih besar di tablet */
        .tablet\:px-4 {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        .tablet\:py-2 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        /* Font size sedikit lebih besar */
        .tablet\:text-sm {
            font-size: 0.875rem !important;
        }

        /* Padding untuk tabel di tablet */
        .tablet\:ml-8 {
            margin-left: 2rem !important;
        }

        .tablet\:mr-8 {
            margin-right: 2rem !important;
        }

        /* Pagination di tablet */
        .tablet\:space-x-2 {
            gap: 0.5rem !important;
        }

        .tablet\:w-9 {
            width: 2.25rem !important;
        }

        .tablet\:h-9 {
            height: 2.25rem !important;
        }
    }

    /* Smooth transitions untuk semua toggle */
    .transition-all {
        transition: all 0.3s ease-in-out;
    }

    .transition-colors {
        transition: all 0.2s ease-in-out;
    }

    /* Animasi loading spinner */
    .animate-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Drag and drop untuk upload area */
    .drag-over {
        border-color: #5B6541 !important;
        background-color: rgba(91, 101, 65, 0.05) !important;
        transform: scale(1.02);
    }
</style>

<script>
    // Deklarasi variabel global
    let currentPopupType = '';
    let deleteUserId = null;
    let deleteUserName = '';
    let searchTimeout = null;

    // Fungsi untuk menampilkan notifikasi - SAMA SEPERTI BANNER
    function showNotification(type, title, message, icon) {
        const container = document.getElementById('notificationContainer');
        const content = document.getElementById('notificationContent');
        const notificationIcon = document.getElementById('notificationIcon');
        const notificationTitle = document.getElementById('notificationTitle');
        const notificationMessage = document.getElementById('notificationMessage');

        const colors = {
            'success': 'bg-gradient-to-br from-green-600 to-green-700',
            'info': 'bg-gradient-to-br from-blue-600 to-blue-700',
            'warning': 'bg-gradient-to-br from-yellow-600 to-yellow-700',
            'error': 'bg-gradient-to-br from-red-600 to-red-700',
            'edit': 'bg-gradient-to-br from-[#5B6541] to-[#4a4f35]',
            'delete': 'bg-gradient-to-br from-[#5B6541] to-[#4a4f35]',
            'add': 'bg-gradient-to-br from-[#5B6541] to-[#4a4f35]',
            'default': 'bg-gradient-to-br from-[#5B6541] to-[#4a4f35]'
        };

        const icons = {
            'success': 'check_circle',
            'info': 'info',
            'warning': 'warning',
            'error': 'error',
            'edit': 'edit',
            'delete': 'delete',
            'add': 'add_circle',
            'default': 'notifications'
        };

        // SAMA SEPERTI BANNER - menggunakan class yang sama
        content.className = `text-white p-3 rounded-lg shadow-lg flex items-center gap-3 animate-slide-down min-w-[300px] max-w-[400px] w-auto mobile:max-w-[80vw] mobile:min-w-[260px] mobile:p-2.5 ${colors[type] || colors['default']}`;
        notificationIcon.textContent = icons[icon] || icons[type] || icons['default'];
        notificationTitle.textContent = title;
        notificationMessage.textContent = message;

        container.classList.remove('hidden');
        container.classList.add('block');

        // Auto close setelah 4 detik
        setTimeout(() => {
            closeNotification();
        }, 4000);
    }

    function closeNotification() {
        const container = document.getElementById('notificationContainer');
        container.classList.remove('block');
        container.classList.add('hidden');
    }

    // ===== REAL-TIME SEARCH =====
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const clearSearchBtn = document.getElementById('clearSearch');
        const searchLoading = document.getElementById('searchLoading');
        const baseUrl = searchInput.dataset.baseUrl;
        const noResults = document.getElementById('noResults');
        const tableContainer = document.querySelector('.ml-6.mr-6.mobile\\:ml-4.mobile\\:mr-4');
        const paginationContainer = document.querySelector('.mt-6.flex');

        // Tampilkan clear button jika ada teks
        if (searchInput.value) {
            clearSearchBtn.classList.remove('opacity-0', 'invisible');
            clearSearchBtn.classList.add('opacity-100', 'visible');
        }

        // Event listener untuk input search
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim();

            // Tampilkan/hide clear button
            if (searchTerm) {
                clearSearchBtn.classList.remove('opacity-0', 'invisible');
                clearSearchBtn.classList.add('opacity-100', 'visible');
            } else {
                clearSearchBtn.classList.remove('opacity-100', 'visible');
                clearSearchBtn.classList.add('opacity-0', 'invisible');
            }

            // Debounce untuk real-time search ke server
            clearTimeout(searchTimeout);

            if (searchTerm.length > 0 && searchTerm.length < 2) {
                return; // Jangan search jika kurang dari 2 karakter
            }

            if (searchTerm.length >= 2 || searchTerm.length === 0) {
                searchLoading.classList.remove('hidden');

                searchTimeout = setTimeout(() => {
                    performSearch(searchTerm);
                }, 500); // Delay 500ms
            }
        });

        // Clear search
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            searchInput.focus();
            clearSearchBtn.classList.remove('opacity-100', 'visible');
            clearSearchBtn.classList.add('opacity-0', 'invisible');
            performSearch('');
        });

        // Enter untuk search
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch(this.value.trim());
            }
        });

        // Fungsi untuk melakukan search ke server
        function performSearch(searchTerm) {
            const status = new URLSearchParams(window.location.search).get('status') || '';
            const url = new URL(baseUrl);
            const params = new URLSearchParams();

            if (searchTerm) params.append('search', searchTerm);
            if (status) params.append('status', status);

            window.location.href = `${url.pathname}?${params.toString()}`;
        }

        // Update pesan no results berdasarkan filter
        if (noResults && !noResults.classList.contains('hidden')) {
            const message = noResults.querySelector('p');
            if (message) {
                if (searchInput.value) {
                    message.innerHTML = `Tidak ditemukan hasil untuk "<span class="font-medium">${searchInput.value}</span>"`;
                }
            }
        }
    });

    // ===== TOGGLE PASSWORD VISIBILITY di FORM =====
    function togglePasswordVisibilityForm(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('span');

        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility_off';
        }
    }

    // ===== PASSWORD STRENGTH CHECKER =====
    document.getElementById('passwordInput')?.addEventListener('input', function(e) {
        const password = e.target.value;
        const strengthDiv = document.getElementById('passwordStrength');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');

        if (password.length === 0) {
            strengthDiv.classList.add('hidden');
            return;
        }

        strengthDiv.classList.remove('hidden');

        let strength = 0;
        let color = 'bg-red-500';
        let text = 'Lemah';

        // Check password strength
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        switch(strength) {
            case 1:
                color = 'bg-red-500';
                text = 'Lemah';
                break;
            case 2:
                color = 'bg-yellow-500';
                text = 'Cukup';
                break;
            case 3:
                color = 'bg-blue-500';
                text = 'Baik';
                break;
            case 4:
                color = 'bg-green-500';
                text = 'Kuat';
                break;
        }

        strengthBar.className = `h-full transition-all duration-300 ${color}`;
        strengthBar.style.width = `${(strength / 4) * 100}%`;
        strengthText.textContent = text;
    });

    // ===== DRAG AND DROP UPLOAD =====
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('uploadArea');
        const editUploadArea = document.getElementById('editUploadArea');

        if (uploadArea) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, unhighlight, false);
            });

            uploadArea.addEventListener('drop', handleDrop, false);
        }

        if (editUploadArea) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                editUploadArea.addEventListener(eventName, preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                editUploadArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                editUploadArea.addEventListener(eventName, unhighlight, false);
            });

            editUploadArea.addEventListener('drop', handleEditDrop, false);
        }

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight() {
            this.classList.add('drag-over');
        }

        function unhighlight() {
            this.classList.remove('drag-over');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files, 'add');
        }

        function handleEditDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files, 'edit');
        }

        function handleFiles(files, type) {
            if (files.length > 0) {
                const file = files[0];
                if (file.type.startsWith('image/')) {
                    if (type === 'add') {
                        document.getElementById('fotoInput').files = files;
                        previewImage({ target: document.getElementById('fotoInput') });
                    } else {
                        document.getElementById('editFotoInput').files = files;
                        previewEditImage({ target: document.getElementById('editFotoInput') });
                    }
                } else {
                    showNotification('error', 'File tidak valid', 'Silakan unggah file gambar (JPG, PNG)', 'error');
                }
            }
        }
    });

    // ===== CLEAR ALL FILTERS =====
    function clearAllFilters() {
        window.location.href = "{{ route('pengguna.index') }}";
    }

    // ===== AUTO-HIDE DROPDOWN =====
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#statusDropdown')) {
                const dropdown = document.querySelector('#statusDropdown .absolute');
                if (dropdown) {
                    dropdown.classList.add('hidden');
                }
            }
        });

        // Status Toggle Handler
        const statusToggle = document.getElementById('statusToggle');
        const statusValue = document.getElementById('statusValue');
        const statusText = document.getElementById('statusText');
        const statusIndicator = document.getElementById('statusIndicator');

        if (statusToggle) {
            const oldStatus = "{{ old('status', 'tidak') }}";
            const isActive = oldStatus === 'aktif';
            statusToggle.checked = isActive;
            statusValue.value = oldStatus;

            if (statusText) {
                statusText.textContent = isActive ? 'Aktif' : 'Tidak Aktif';
            }
            if (statusIndicator) {
                statusIndicator.style.backgroundColor = isActive ? '#10B981' : '#EF4444';
            }

            statusToggle.addEventListener('change', function() {
                const isActive = statusToggle.checked;
                statusValue.value = isActive ? 'aktif' : 'tidak';

                if (statusText) {
                    statusText.textContent = isActive ? 'Aktif' : 'Tidak Aktif';
                }
                if (statusIndicator) {
                    statusIndicator.style.backgroundColor = isActive ? '#10B981' : '#EF4444';
                }
            });
        }

        // Edit Status Toggle Handler
        const editStatusToggle = document.getElementById('editStatusToggle');
        const editStatusValue = document.getElementById('editStatusValue');
        const editStatusText = document.getElementById('editStatusText');
        const editStatusIndicator = document.getElementById('editStatusIndicator');

        if (editStatusToggle) {
            editStatusToggle.addEventListener('change', function() {
                const isActive = editStatusToggle.checked;
                editStatusValue.value = isActive ? 'aktif' : 'tidak';

                if (editStatusText) {
                    editStatusText.textContent = isActive ? 'Aktif' : 'Tidak Aktif';
                }
                if (editStatusIndicator) {
                    editStatusIndicator.style.backgroundColor = isActive ? '#10B981' : '#EF4444';
                }
            });
        }

        @if(session('success'))
            showNotification(
                '{{ session('type', 'success') }}',
                '{{ session('success') }}',
                '{{ session('message', '') }}',
                '{{ session('icon', 'check_circle') }}'
            );
        @endif

        @if(session('error'))
            showNotification(
                'error',
                '{{ session('error') }}',
                '',
                'error'
            );
        @endif

        @if($errors->any() && session('_type') == 'store')
            showNotification(
                'warning',
                'Periksa kembali data yang Anda masukkan.',
                '',
                'warning'
            );
        @endif

        // Upload area click handlers
        const uploadArea = document.querySelector('#popupOverlay .cursor-pointer');
        if (uploadArea) {
            uploadArea.addEventListener('click', function() {
                document.getElementById('fotoInput').click();
            });
        }

        const editUploadArea = document.querySelector('#editPopupOverlay .cursor-pointer');
        if (editUploadArea) {
            editUploadArea.addEventListener('click', function() {
                document.getElementById('editFotoInput').click();
            });
        }
    });

    // ===== FUNGSI POPUP =====
    function openPopup() {
        currentPopupType = 'add';
        document.getElementById('popupOverlay').classList.remove('hidden');
        document.getElementById('popupOverlay').classList.add('flex');
        document.body.style.overflow = 'hidden';
        resetForm();
    }

    function closePopup() {
        document.getElementById('popupOverlay').classList.remove('flex');
        document.getElementById('popupOverlay').classList.add('hidden');
        document.body.style.overflow = 'auto';
        resetForm();
    }

    function resetForm() {
        document.getElementById('addUserForm').reset();
        document.getElementById('uploadIcon').classList.remove('hidden');
        document.getElementById('imagePreviewThumbnail').classList.add('hidden');
        document.getElementById('statusToggle').checked = false;
        document.getElementById('statusValue').value = 'tidak';

        // Update UI status
        const statusText = document.getElementById('statusText');
        const statusIndicator = document.getElementById('statusIndicator');
        if (statusText) statusText.textContent = 'Tidak Aktif';
        if (statusIndicator) statusIndicator.style.backgroundColor = '#EF4444';

        document.getElementById('passwordStrength').classList.add('hidden');
    }

    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreviewThumbnail');
        const uploadIcon = document.getElementById('uploadIcon');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                uploadIcon.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openEditPopup(id, nama, email, status, fotoUrl) {
        currentPopupType = 'edit';
        document.getElementById('editUserForm').action = `/pengguna/${id}`;

        document.getElementById('editNama').value = nama;
        document.getElementById('editEmail').value = email;

        const isAktif = status === 'aktif';
        document.getElementById('editStatusToggle').checked = isAktif;
        document.getElementById('editStatusValue').value = isAktif ? 'aktif' : 'tidak';

        // Update UI status
        const editStatusText = document.getElementById('editStatusText');
        const editStatusIndicator = document.getElementById('editStatusIndicator');
        if (editStatusText) editStatusText.textContent = isAktif ? 'Aktif' : 'Tidak Aktif';
        if (editStatusIndicator) editStatusIndicator.style.backgroundColor = isAktif ? '#10B981' : '#EF4444';

        const fotoContainer = document.getElementById('currentFotoContainer');
        fotoContainer.innerHTML = '';

        if (fotoUrl) {
            const img = document.createElement('img');
            img.src = fotoUrl;
            img.className = 'w-full h-full object-cover rounded-full animate-fadeIn';
            img.id = 'editCurrentFoto';
            fotoContainer.appendChild(img);
        } else {
            const icon = document.createElement('span');
            icon.className = 'material-icons text-gray-300 text-7xl mobile:text-5xl';
            icon.id = 'editUploadIcon';
            icon.textContent = 'add_a_photo';
            fotoContainer.appendChild(icon);
        }

        document.getElementById('editPopupOverlay').classList.remove('hidden');
        document.getElementById('editPopupOverlay').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeEditPopup() {
        document.getElementById('editPopupOverlay').classList.remove('flex');
        document.getElementById('editPopupOverlay').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function previewEditImage(event) {
        const input = event.target;
        const fotoContainer = document.getElementById('currentFotoContainer');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                fotoContainer.innerHTML = '';
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-full object-cover rounded-full animate-fadeIn';
                img.id = 'editCurrentFoto';
                fotoContainer.appendChild(img);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openDeleteConfirmation(id, nama) {
        deleteUserId = id;
        deleteUserName = nama;

        document.getElementById('deleteMessage').textContent =
            `Apakah Anda yakin ingin menghapus pengguna "${nama}"?`;

        document.getElementById('deleteForm').action = `/pengguna/${id}`;

        document.getElementById('deleteConfirmationOverlay').classList.remove('hidden');
        document.getElementById('deleteConfirmationOverlay').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteConfirmation() {
        document.getElementById('deleteConfirmationOverlay').classList.remove('flex');
        document.getElementById('deleteConfirmationOverlay').classList.add('hidden');
        document.body.style.overflow = 'auto';
        deleteUserId = null;
        deleteUserName = '';
    }

    function openCancelConfirmation(type) {
        currentPopupType = type;
        document.getElementById('cancelConfirmationOverlay').classList.remove('hidden');
        document.getElementById('cancelConfirmationOverlay').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeCancelConfirmation() {
        document.getElementById('cancelConfirmationOverlay').classList.remove('flex');
        document.getElementById('cancelConfirmationOverlay').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function confirmCancel() {
        closeCancelConfirmation();
        if (currentPopupType === 'add') {
            closePopup();
        } else if (currentPopupType === 'edit') {
            closeEditPopup();
        }
        currentPopupType = '';
    }

    // Close popup when clicking outside
    document.getElementById('popupOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            openCancelConfirmation('add');
        }
    });

    document.getElementById('editPopupOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            openCancelConfirmation('edit');
        }
    });

    document.getElementById('deleteConfirmationOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteConfirmation();
        }
    });

    document.getElementById('cancelConfirmationOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCancelConfirmation();
        }
    });

    // Close popup with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('deleteConfirmationOverlay').classList.contains('hidden')) {
                closeDeleteConfirmation();
            } else if (!document.getElementById('cancelConfirmationOverlay').classList.contains('hidden')) {
                closeCancelConfirmation();
            } else if (!document.getElementById('popupOverlay').classList.contains('hidden')) {
                openCancelConfirmation('add');
            } else if (!document.getElementById('editPopupOverlay').classList.contains('hidden')) {
                openCancelConfirmation('edit');
            }
        }
    });

    @if($errors->any() && old('_token') && !old('_method'))
    document.addEventListener('DOMContentLoaded', function() {
        openPopup();
    });
    @endif

    window.showCustomNotification = function(type, title, message, icon) {
        showNotification(type, title, message, icon);
    };
</script>
@endsection
