@extends('layouts.app')

@section('title', 'Layanan')

@section('content')
<br>

<h2 class="text-2xl font-bold text-[#5B6541] mb-6 ml-6 mobile:ml-0 mobile:text-lg mobile:mb-4">Layanan</h2>

<!-- SISTEM NOTIFIKASI UNIFIED -->
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
    <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative overflow-hidden border border-[#5B6541] mobile:max-w-[90vw]">
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
                    Data layanan akan dihapus secara permanen
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

<!-- Container utama untuk filter dan tombol -->
<div
    class="flex items-center justify-between mb-5 ml-6 mr-6 mobile:ml-0 mobile:mr-0 mobile:mb-3 mobile:px-0 mobile:gap-1 mobile:w-full mobile:justify-start mobile:overflow-x-auto mobile:no-scrollbar">
    <!-- Search - REAL-TIME SEARCH -->
    <div class="flex items-center gap-1 mobile:flex-shrink-0">
        <div class="relative group">
            <span
                class="material-icons absolute left-2 top-1.5 text-[#5B6541] text-sm mobile:text-xs mobile:left-1.5 mobile:top-1 pointer-events-none">search</span>
            <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
                class="pl-7 pr-8 py-1.5 border border-[#5B6541] text-[#5B6541] rounded-lg w-48 mobile:w-28 mobile:text-xs mobile:py-1 mobile:pl-6 mobile:pr-7 bg-[#F7F6F2] mobile:text-xs transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#5B6541]/30 focus:border-[#5B6541]"
                placeholder="cari..." data-base-url="{{ route('layanan.index') }}">
            <!-- Clear button yang muncul jika ada teks -->
            <button id="clearSearch"
                class="absolute right-2 top-1.5 text-[#5B6541] opacity-0 invisible transition-all duration-200 hover:text-[#4a4f35] mobile:right-1.5 mobile:top-1">
                <span class="material-icons text-sm mobile:text-xs">close</span>
            </button>
            <button
                class="px-3 py-1.5 bg-[#5B6541] text-white rounded-lg mobile:px-2 mobile:py-1 mobile:text-xs whitespace-nowrap">Cari</button>
        </div>
        <div id="searchLoading" class="hidden ml-2">
            <div class="w-4 h-4 border-2 border-[#5B6541] border-t-transparent rounded-full animate-spin"></div>
        </div>
    </div>

    <!-- Status dan Tambah Button -->
    <div class="flex items-center gap-2 mobile:flex-shrink-0 mobile:gap-1 mobile:ml-1">
        <!-- Dropdown Status -->
        <div class="relative">
            <div id="statusDropdown" class="relative cursor-pointer group">
                <div
                    class="px-2 py-1.5 border border-[#5B6541] rounded-lg bg-[#5B6541] text-white cursor-pointer mobile:px-1.5 mobile:py-1 mobile:text-xs whitespace-nowrap flex items-center gap-1 hover:bg-[#4a4f35] transition-colors">
                    <span class="material-icons text-xs mobile:text-xs">filter_list</span>
                    <span>Status</span>
                    @if(request('status'))
                    <span class="w-1.5 h-1.5 bg-yellow-400 rounded-full ml-1 animate-pulse"></span>
                    @endif
                </div>
                <!-- Dropdown menu -->
                <div
                    class="absolute top-full left-0 mt-1 w-32 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-10 group-hover:block hover:block">
                    <form action="{{ route('layanan.index') }}" method="GET" id="statusForm">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <button type="submit" name="status" value=""
                            class="w-full text-left px-3 py-2 hover:bg-gray-100 text-gray-700 flex items-center justify-between {{ !request('status') ? 'bg-gray-100 font-medium' : '' }}">
                            <span>Semua</span>
                            @if(!request('status'))<span
                                class="material-icons text-[#5B6541] text-sm">check</span>@endif
                        </button>
                        <button type="submit" name="status" value="1"
                            class="w-full text-left px-3 py-2 hover:bg-gray-100 text-gray-700 flex items-center justify-between {{ request('status') == '1' ? 'bg-gray-100 font-medium' : '' }}">
                            <span>Aktif</span>
                            @if(request('status') == '1')<span
                                class="material-icons text-[#5B6541] text-sm">check</span>@endif
                        </button>
                        <button type="submit" name="status" value="0"
                            class="w-full text-left px-3 py-2 hover:bg-gray-100 text-gray-700 flex items-center justify-between {{ request('status') == '0' ? 'bg-gray-100 font-medium' : '' }}">
                            <span>Tidak</span>
                            @if(request('status') == '0')<span
                                class="material-icons text-[#5B6541] text-sm">check</span>@endif
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tambah Button -->
        <button onclick="openPopup()"
            class="px-3 py-2 bg-[#5B6541] text-white rounded-lg flex items-center gap-0.5 mobile:px-2 mobile:py-1 hover:bg-[#4a4f35] transform hover:-translate-y-0.5 transition-all duration-200 active:scale-95">
            <span class="material-icons text-sm mobile:text-xs">add</span>
            <span class="mobile:hidden text-xs"></span>
        </button>
    </div>
</div>

<!-- PESAN KOSONG -->
<div id="noResults" class="{{ $layanan->count() > 0 ? 'hidden' : '' }} ml-6 mr-6 mobile:ml-4 mobile:mr-4 mb-4">
    <div class="bg-[#F7F6F2] border border-[#5B6541] shadow-lg rounded-lg p-8 text-center">
        <span class="material-icons text-[#5B6541] text-4xl mb-3">restaurant</span>
        <h3 class="text-lg font-semibold text-[#5B6541] mb-2">Tidak ada layanan ditemukan</h3>
        <p class="text-gray-600">
            @if(request('search'))
            Tidak ditemukan hasil untuk "<span class="font-medium">{{ request('search') }}</span>"
            @elseif(request('status'))
            Tidak ada layanan dengan status "<span class="font-medium">{{ request('status') == '1' ? 'Aktif' : 'Tidak'
                }}</span>"
            @else
            Belum ada data layanan yang ditambahkan
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



<!-- Table Container -->
<div class="ml-6 mr-6 mobile:ml-4 mobile:mr-4 mobile:overflow-x-auto {{ $layanan->count() == 0 ? 'hidden' : '' }}">
    <div class="bg-[#F7F6F2] border border-[#5B6541] shadow-lg rounded-lg overflow-hidden mobile:min-w-[800px]">
        <table class="w-full text-left">
            <thead class="bg-[#5B6541] text-white">
                <tr>
                    <th class="p-3 pl-6 mobile:p-2 mobile:pl-4 mobile:text-sm">No</th>
                    <th class="p-3 mobile:p-2 mobile:text-sm">Foto</th>
                    <th class="p-3 mobile:p-2 mobile:text-sm">Nama Layanan</th>
                    <th class="p-3 mobile:p-2 mobile:text-sm">Deskripsi</th>
                    <th class="p-3 mobile:p-2 mobile:text-sm">Status</th>
                    <th class="p-3 pr-6 mobile:p-2 mobile:pr-4 mobile:text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#5B6541]">
                @foreach($layanan as $item)
                <tr class="hover:bg-[#eceadd] transition-colors duration-200">
                    <!-- No -->
                    <td class="p-3 pl-6 mobile:p-2 mobile:pl-4 text-[#5B6541] font-semibold mobile:text-sm">
                        {{ ($layanan->currentPage() - 1) * $layanan->perPage() + $loop->iteration }}
                    </td>

                    <!-- Foto -->
                    <td class="p-3 mobile:p-2">
                        <img src="{{ asset('storage/'.$item->foto_layanan) }}"
                            class="w-32 h-24 rounded object-cover mobile:w-16 mobile:h-12">
                    </td>

                    <!-- Nama Layanan -->
                    <td class="p-3 text-[#5B6541] font-semibold mobile:p-2 mobile:text-sm">
                        {{ $item->nama_layanan }}
                    </td>

                    <!-- Deskripsi -->
                    <td class="p-3 text-[#5B6541] mobile:p-2 mobile:text-sm">
                        <div class="max-w-xs">
                            {{ \Illuminate\Support\Str::limit($item->deskripsi_layanan, 60) }}
                        </div>
                    </td>

                    <!-- Badge Status -->
                    <td class="p-3 mobile:p-2">
                        @if($item->status == 1)
                        <span
                            class="px-6 py-2 bg-[#5B6541] text-white text-sm rounded-md font-medium mobile:px-2 mobile:py-1 mobile:text-xs">
                            Aktif
                        </span>
                        @else
                        <span
                            class="px-6 py-2 bg-[#5D2222] text-white text-sm rounded-md font-medium mobile:px-2 mobile:py-1 mobile:text-xs">
                            Tidak
                        </span>
                        @endif
                    </td>

                    <!-- Aksi -->
                    <td class="p-3 pr-6 mobile:p-2 mobile:pr-4">
                        <div class="flex gap-2 mobile:gap-1">
                            <button type="button" onclick="openEditPopup(
                                    '{{ $item->id }}',
                                    '{{ $item->nama_layanan }}',
                                    `{{ addslashes($item->deskripsi_layanan) }}`,
                                    '{{ asset('storage/'.$item->foto_layanan) }}',
                                    '{{ $item->status }}'
                                )"
                                class="px-4 py-2 border border-[#5B6541] text-[#5B6541] rounded-lg text-sm flex items-center gap-1 hover:bg-[#5B6541] hover:text-white transition-all duration-200 hover:scale-105 active:scale-95 mobile:px-2 mobile:py-1 mobile:text-xs">
                                <span class="material-icons text-sm mobile:text-xs">edit</span>
                                <span class="mobile:hidden">Edit</span>
                            </button>
                            <button type="button"
                                onclick="openDeleteConfirmation('{{ $item->id }}', '{{ $item->nama_layanan }}')"
                                class="px-4 py-2 border border-[#5B6541] text-[#5B6541] rounded-lg text-sm flex items-center gap-1 hover:bg-[#5B6541] hover:text-white transition-all duration-200 hover:scale-105 active:scale-95 mobile:px-2 mobile:py-1 mobile:text-xs">
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

<!-- PAGINATION -->
<div class="mt-6 flex justify-center px-6 mobile:px-4 mobile:mt-4 {{ $layanan->count() == 0 ? 'hidden' : '' }}">
    @if ($layanan->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center space-x-1 mobile:space-x-0.5">
        {{-- Previous Page Link --}}
        @if (!$layanan->onFirstPage())
        <a href="{{ $layanan->previousPageUrl() }}"
            class="w-8 h-8 flex items-center justify-center text-[#5B6541] hover:text-[#4a4f35] hover:bg-gray-100 rounded transition-all duration-200 hover:scale-110 mobile:w-7 mobile:h-7">
            <span class="material-icons text-lg mobile:text-base">chevron_left</span>
        </a>
        @endif

        {{-- Pagination Elements --}}
        @php
        $current = $layanan->currentPage();
        $last = $layanan->lastPage();
        $start = max(1, $current - 1);
        $end = min($last, $current + 1);
        @endphp

        @if($start > 1 && $current > 2)
        <a href="{{ $layanan->url(1) }}"
            class="w-8 h-8 flex items-center justify-center text-[#5B6541] hover:text-[#4a4f35] hover:bg-gray-100 rounded transition-all duration-200 hover:scale-110 mobile:w-7 mobile:h-7 mobile:text-sm">1</a>
        @if($start > 2)
        <span class="w-8 h-8 flex items-center justify-center text-gray-400 mobile:w-7 mobile:h-7">...</span>
        @endif
        @endif

        @for($page = $start; $page <= $end; $page++) @if($page==$current) <span
            class="w-8 h-8 flex items-center justify-center bg-[#5B6541] text-white rounded font-medium mobile:w-7 mobile:h-7 mobile:text-sm transform scale-110 transition-transform duration-200">
            {{ $page }}
            </span>
            @else
            <a href="{{ $layanan->url($page) }}"
                class="w-8 h-8 flex items-center justify-center text-[#5B6541] hover:text-[#4a4f35] hover:bg-gray-100 rounded transition-all duration-200 hover:scale-110 mobile:w-7 mobile:h-7 mobile:text-sm">
                {{ $page }}
            </a>
            @endif
            @endfor

            @if($end < $last) @if($end < $last - 1) <span
                class="w-8 h-8 flex items-center justify-center text-gray-400 mobile:w-7 mobile:h-7">...</span>
                @endif
                <a href="{{ $layanan->url($last) }}"
                    class="w-8 h-8 flex items-center justify-center text-[#5B6541] hover:text-[#4a4f35] hover:bg-gray-100 rounded transition-all duration-200 hover:scale-110 mobile:w-7 mobile:h-7 mobile:text-sm">
                    {{ $last }}
                </a>
                @endif

                {{-- Next Page Link --}}
                @if ($layanan->hasMorePages())
                <a href="{{ $layanan->nextPageUrl() }}"
                    class="w-8 h-8 flex items-center justify-center text-[#5B6541] hover:text-[#4a4f35] hover:bg-gray-100 rounded transition-all duration-200 hover:scale-110 mobile:w-7 mobile:h-7">
                    <span class="material-icons text-lg mobile:text-base">chevron_right</span>
                </a>
                @endif
    </nav>
    @endif
</div>

<!-- POPUP TAMBAH LAYANAN -->
<div id="popupOverlay"
    class="fixed inset-0 bg-black bg-opacity-30 z-50 hidden items-center justify-center p-4 mobile:p-2">
    <div
        class="bg-[#F7F6F2] rounded-2xl shadow-2xl w-full max-w-4xl relative overflow-hidden border border-[#5B6541] mobile:rounded-xl mobile:max-h-[90vh] mobile:overflow-y-auto mobile:w-[95vw]">
        <form id="addLayananForm" action="{{ route('layanan.store') }}" method="POST" enctype="multipart/form-data"
            class="p-6 mobile:p-4">
            @csrf

            <!-- Title -->
            <div class="mb-6 mobile:mb-4">
                <h3 class="text-xl font-bold text-[#5B6541] mobile:text-lg flex items-center gap-2">
                    <span class="material-icons text-lg">add_circle</span>
                    Tambah Layanan
                </h3>
            </div>

            <!-- Error Messages -->
            @if($errors->any() && old('_token') && !old('_method'))
            <div
                class="mb-6 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg mobile:p-3 mobile:text-sm mobile:mb-4 animate-pulse">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mobile:gap-6">

                <!-- KIRI : GAMBAR -->
                <div>
                    <h4 class="text-lg font-semibold text-[#5B6541] mb-4 mobile:text-base flex items-center gap-2">
                        Foto Layanan *
                    </h4>

                    <div id="uploadArea" onclick="document.getElementById('fotoLayananInput').click()"
                        class="border-2 border-dashed border-[#5B6541] rounded-xl p-6 text-center bg-white cursor-pointer hover:bg-gray-50 transition-all duration-300 hover:border-[#4a4f35] hover:shadow-lg mobile:p-4">

                        <div class="w-48 h-32 mx-auto relative mb-4 mobile:w-40 mobile:h-28">
                            <span id="uploadIcon"
                                class="material-icons text-gray-300 text-5xl absolute inset-0 flex items-center justify-center mobile:text-4xl group-hover:text-[#5B6541] transition-colors">
                                add_a_photo
                            </span>
                            <img id="imagePreview"
                                class="absolute inset-0 w-full h-full object-cover rounded-lg hidden animate-fadeIn">
                        </div>

                        <p class="text-sm text-gray-600 mb-2 mobile:text-xs">Drag & drop atau klik untuk mengunggah
                            gambar</p>
                        <p class="text-xs text-gray-400 mobile:text-xs">Format: JPG, PNG, GIF maks. 5MB</p>
                    </div>

                    <input type="file" id="fotoLayananInput" name="foto_layanan" accept="image/*" class="hidden" required
                        onchange="previewImage(event)">

                    @error('foto_layanan')
                    <p class="text-red-600 text-sm mt-2 mobile:text-xs animate-pulse">{{ $message }}</p>
                    @enderror
                </div>

                <!-- KANAN : FORM INPUT -->
                <div class="flex flex-col justify-between mobile:gap-6">

                    <!-- NAMA LAYANAN -->
                    <div>
                        <label
                            class="block text-sm font-semibold text-[#5B6541] mb-2 mobile:text-sm">
                            Nama Layanan
                        </label>
                        <div
                            class="border border-dashed border-gray-300 rounded-lg p-1 mobile:p-0.5 hover:border-[#5B6541] transition-colors duration-300">
                            <input type="text" name="nama_layanan" value="{{ old('nama_layanan') }}"
                                class="w-full px-4 py-3 bg-transparent focus:outline-none text-gray-800 placeholder-gray-400 mobile:px-3 mobile:py-2 mobile:text-sm transition-all duration-300"
                                placeholder="Masukkan nama layanan" required
                                onfocus="this.parentElement.style.borderColor = '#5B6541'"
                                onblur="this.parentElement.style.borderColor = ''">
                        </div>
                        @error('nama_layanan')
                        <p class="text-red-600 text-sm mt-2 mobile:text-xs animate-pulse">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- DESKRIPSI -->
                    <div>
                        <label
                            class="block text-sm font-semibold text-[#5B6541] mb-2 mobile:text-sm">
                            Deskripsi Layanan
                        </label>
                        <div
                            class="border border-dashed border-gray-300 rounded-lg p-1 mobile:p-0.5 hover:border-[#5B6541] transition-colors duration-300">
                            <textarea name="deskripsi_layanan" rows="3"
                                class="w-full px-4 py-3 bg-transparent focus:outline-none text-gray-800 placeholder-gray-400 mobile:px-3 mobile:py-2 mobile:text-sm resize-none transition-all duration-300"
                                placeholder="Masukkan deskripsi layanan" required maxlength="350"
                                onfocus="this.parentElement.style.borderColor = '#5B6541'"
                                onblur="this.parentElement.style.borderColor = ''">{{ old('deskripsi_layanan') }}</textarea>
                        </div>
                        @error('deskripsi_layanan')
                        <p class="text-red-600 text-sm mt-2 mobile:text-xs animate-pulse">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- STATUS TOGGLE -->
                    <div>
                        <label
                            class="block text-sm font-semibold text-[#5B6541] mb-2 mobile:text-sm">
                            Status Layanan
                        </label>

                        <div class="flex items-center gap-3 mobile:gap-2">
                            <!-- Toggle Container -->
                            <div class="relative">
                                <input type="checkbox" id="statusToggle" class="sr-only peer" {{ old('status', '1'
                                    )=='1' ? 'checked' : '' }}>
                                <label for="statusToggle" class="cursor-pointer">
                                    <div class="toggle-container">
                                        <div class="toggle-circle"></div>
                                    </div>
                                </label>
                            </div>

                            <span class="text-gray-700 font-medium mobile:text-sm" id="statusText">
                                {{ old('status', '1') == '1' ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                            <span class="w-2.5 h-2.5 rounded-full {{ old('status', '1') == '1' ? 'bg-green-500' : 'bg-red-500' }} mobile:w-2 mobile:h-2"
                                id="statusIndicator"></span>
                        </div>

                        <!-- Hidden input untuk mengirim nilai status -->
                        <input type="hidden" name="status" id="statusValue" value="{{ old('status', '1') }}">
                        @error('status')
                        <p class="text-red-600 text-sm mt-2 mobile:text-xs animate-pulse">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="mt-8 pt-6 border-t border-dashed border-gray-300 mobile:mt-6 mobile:pt-4">
                <div class="flex justify-between items-center mobile:flex-col mobile:gap-3">
                    <button type="button" onclick="openCancelConfirmation('add')"
                        class="px-8 py-2.5 border-2 border-[#5B6541] text-[#5B6541] rounded-lg hover:bg-[#5B6541] hover:text-white transition-all duration-200 hover:scale-105 active:scale-95 mobile:px-6 mobile:py-2 mobile:text-sm mobile:w-full flex items-center justify-center gap-2">
                        <span class="material-icons text-lg mobile:text-base">close</span>
                        Tutup
                    </button>

                    <button type="submit"
                        class="px-8 py-2.5 bg-[#5B6541] text-white rounded-lg hover:bg-[#4a4f35] transition-all duration-200 hover:scale-105 active:scale-95 mobile:px-6 mobile:py-2 mobile:text-sm mobile:w-full flex items-center justify-center gap-2">
                        <span class="material-icons text-lg mobile:text-base">save</span>
                        Simpan
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- POPUP EDIT LAYANAN -->
<div id="editPopupOverlay"
    class="fixed inset-0 bg-black bg-opacity-30 z-50 hidden items-center justify-center p-4 mobile:p-2">
    <div
        class="bg-[#F7F6F2] rounded-2xl shadow-2xl w-full max-w-4xl relative overflow-hidden border border-[#5B6541] mobile:rounded-xl mobile:max-h-[90vh] mobile:overflow-y-auto mobile:w-[95vw]">
        <form id="editLayananForm" method="POST" enctype="multipart/form-data" class="p-6 mobile:p-4">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-6 mobile:mb-4">
                <h3 class="text-xl font-bold text-[#5B6541] mobile:text-lg flex items-center gap-2">
                    Edit Layanan
                </h3>
            </div>

            <!-- Error Messages untuk Edit -->
            @if($errors->any() && old('_method') == 'PUT')
            <div
                class="mb-6 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg mobile:p-3 mobile:text-sm mobile:mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mobile:gap-6">

                <!-- KIRI : GAMBAR -->
                <div>
                    <h4 class="text-lg font-semibold text-[#5B6541] mb-4 mobile:text-base flex items-center gap-2">
                        Foto Layanan
                    </h4>

                    <div id="editUploadArea" onclick="document.getElementById('editFotoLayananInput').click()"
                        class="border-2 border-dashed border-[#5B6541] rounded-xl p-6 text-center bg-white cursor-pointer hover:bg-gray-50 transition-all duration-300 hover:border-[#4a4f35] hover:shadow-lg mobile:p-4">

                        <div class="w-48 h-32 mx-auto relative mb-4 mobile:w-40 mobile:h-28">
                            <span id="editUploadIcon"
                                class="material-icons text-gray-300 text-5xl absolute inset-0 flex items-center justify-center mobile:text-4xl group-hover:text-[#5B6541] transition-colors">
                                add_a_photo
                            </span>
                            <img id="editImagePreview"
                                class="absolute inset-0 w-full h-full object-cover rounded-lg animate-fadeIn">
                        </div>

                        <p class="text-sm text-gray-600 mb-2 mobile:text-xs">Klik untuk mengubah gambar</p>
                        <p class="text-xs text-gray-400 mobile:text-xs">Format: JPG, PNG, GIF maks. 5MB</p>
                    </div>

                    <input type="file" id="editFotoLayananInput" name="foto_layanan" accept="image/*" class="hidden"
                        onchange="previewEditImage(event)">
                </div>

                <!-- KANAN : FORM INPUT -->
                <div class="flex flex-col justify-between mobile:gap-6">

                    <!-- NAMA LAYANAN -->
                    <div>
                        <label
                            class="block text-sm font-semibold text-[#5B6541] mb-2 mobile:text-sm">
                            Nama Layanan
                        </label>
                        <div
                            class="border border-dashed border-gray-300 rounded-lg p-1 mobile:p-0.5 hover:border-[#5B6541] transition-colors duration-300">
                            <input type="text" name="nama_layanan" id="editNamaLayanan"
                                class="w-full px-4 py-3 bg-transparent focus:outline-none text-gray-800 placeholder-gray-400 mobile:px-3 mobile:py-2 mobile:text-sm transition-all duration-300"
                                required onfocus="this.parentElement.style.borderColor = '#5B6541'"
                                onblur="this.parentElement.style.borderColor = ''">
                        </div>
                    </div>

                    <!-- DESKRIPSI -->
                    <div>
                        <label
                            class="block text-sm font-semibold text-[#5B6541] mb-2 mobile:text-sm">
                            Deskripsi Layanan
                        </label>
                        <div
                            class="border border-dashed border-gray-300 rounded-lg p-1 mobile:p-0.5 hover:border-[#5B6541] transition-colors duration-300">
                            <textarea name="deskripsi_layanan" id="editDeskripsiLayanan" rows="3"
                                class="w-full px-4 py-3 bg-transparent focus:outline-none text-gray-800 placeholder-gray-400 mobile:px-3 mobile:py-2 mobile:text-sm resize-none transition-all duration-300"
                                required maxlength="350"
                                onfocus="this.parentElement.style.borderColor = '#5B6541'"
                                onblur="this.parentElement.style.borderColor = ''"></textarea>
                        </div>
                    </div>

                    <!-- STATUS TOGGLE -->
                    <div>
                        <label
                            class="block text-sm font-semibold text-[#5B6541] mb-2 mobile:text-sm">
                            Status Layanan
                        </label>

                        <div class="flex items-center gap-3 mobile:gap-2">
                            <!-- Toggle Container -->
                            <div class="relative">
                                <input type="checkbox" id="editStatusToggle" class="sr-only peer">
                                <label for="editStatusToggle" class="cursor-pointer">
                                    <div class="toggle-container">
                                        <div class="toggle-circle"></div>
                                    </div>
                                </label>
                            </div>

                            <span id="editStatusText" class="text-gray-700 font-medium mobile:text-sm">Tidak Aktif</span>
                            <span id="editStatusIndicator"
                                class="w-2.5 h-2.5 rounded-full bg-red-500 mobile:w-2 mobile:h-2"></span>
                        </div>

                        <input type="hidden" name="status" id="editStatusValue" value="0">
                    </div>

                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="mt-8 pt-6 border-t border-dashed border-gray-300 mobile:mt-6 mobile:pt-4">
                <div class="flex justify-between items-center mobile:flex-col mobile:gap-3">
                    <button type="button" onclick="openCancelConfirmation('edit')"
                        class="px-8 py-2.5 border-2 border-[#5B6541] text-[#5B6541] rounded-lg hover:bg-[#5B6541] hover:text-white transition-all duration-200 hover:scale-105 active:scale-95 mobile:px-6 mobile:py-2 mobile:text-sm mobile:w-full flex items-center justify-center gap-2">
                        <span class="material-icons text-lg mobile:text-base">close</span>
                        Tutup
                    </button>

                    <button type="submit"
                        class="px-8 py-2.5 bg-[#5B6541] text-white rounded-lg hover:bg-[#4a4f35] transition-all duration-200 hover:scale-105 active:scale-95 mobile:px-6 mobile:py-2 mobile:text-sm mobile:w-full flex items-center justify-center gap-2">
                        <span class="material-icons text-lg mobile:text-base">update</span>
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
    <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative overflow-hidden border border-[#5B6541] mobile:max-w-[90vw]">
        <div class="p-6 mobile:p-4">
            <!-- Icon -->
            <div class="flex justify-center mb-4 mobile:mb-3">
                <div
                    class="w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center mobile:w-12 mobile:h-12 animate-pulse">
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
                    Seluruh perubahan data layanan tidak akan tersimpan
                </p>
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
    /* Animasi */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

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

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }

    .animate-slide-down {
        animation: slideDown 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    .animate-pulse {
        animation: pulse 1.5s ease-in-out infinite;
    }

    /* TOGGLE */
    .toggle-container {
        width: 52px;
        height: 26px;
        background-color: #e5e7eb !important;
        border-radius: 9999px;
        position: relative;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .toggle-circle {
        position: absolute;
        top: 3px;
        left: 3px;
        width: 20px;
        height: 20px;
        background-color: white;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    /* Posisi circle saat checked */
    #statusToggle:checked + label .toggle-container .toggle-circle,
    #editStatusToggle:checked + label .toggle-container .toggle-circle {
        transform: translateX(26px) !important;
    }

    #statusToggle:checked + label .toggle-container,
    #editStatusToggle:checked + label .toggle-container {
        background-color: #5B6541 !important;
    }

    /* Efek hover */
    #statusToggle + label .toggle-container:hover,
    #editStatusToggle + label .toggle-container:hover {
        background-color: #d1d5db !important;
    }

    #statusToggle:checked + label .toggle-container:hover,
    #editStatusToggle:checked + label .toggle-container:hover {
        background-color: #4a4f35 !important;
    }

    /* Untuk mobile */
    @media (max-width: 768px) {
        .toggle-container {
            width: 48px;
            height: 24px;
        }

        .toggle-circle {
            width: 18px;
            height: 18px;
            top: 3px;
            left: 3px;
        }

        #statusToggle:checked + label .toggle-container .toggle-circle,
        #editStatusToggle:checked + label .toggle-container .toggle-circle {
            transform: translateX(24px) !important;
        }

        /* Untuk search, status, tambah button */
        .mobile\:ml-0 {
            margin-left: 0 !important;
        }

        .mobile\:mr-0 {
            margin-right: 0 !important;
        }

        .mobile\:mb-3 {
            margin-bottom: 0.75rem !important;
        }

        .mobile\:px-0 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .mobile\:gap-1 {
            gap: 0.25rem !important;
        }

        /* Untuk tabel */
        .mobile\:ml-4 {
            margin-left: 1rem !important;
        }

        .mobile\:mr-4 {
            margin-right: 1rem !important;
        }

        .mobile\:p-2 {
            padding: 0.5rem !important;
        }

        .mobile\:pl-4 {
            padding-left: 1rem !important;
        }

        .mobile\:pr-4 {
            padding-right: 1rem !important;
        }

        /* Untuk popup */
        .mobile\:px-4 {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        /* Ukuran font */
        .mobile\:text-xs {
            font-size: 0.75rem !important;
        }

        .mobile\:text-sm {
            font-size: 0.875rem !important;
        }

        /* Ukuran elemen */
        .mobile\:w-16 {
            width: 4rem !important;
        }

        .mobile\:h-12 {
            height: 3rem !important;
        }

        .mobile\:w-40 {
            width: 10rem !important;
        }

        .mobile\:h-28 {
            height: 7rem !important;
        }

        .mobile\:w-2 {
            width: 0.5rem !important;
        }

        .mobile\:h-2 {
            height: 0.5rem !important;
        }

        /* Padding button */
        .mobile\:px-2 {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        .mobile\:py-1 {
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
        }

        .mobile\:px-6 {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }

        .mobile\:py-2 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .mobile\:px-3 {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }

        .mobile\:py-2 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        /* Untuk search box */
        .mobile\:w-28 {
            width: 7rem !important;
        }

        /* Layout */
        .mobile\:hidden {
            display: none !important;
        }

        .mobile\:flex-col {
            flex-direction: column !important;
        }

        .mobile\:w-full {
            width: 100% !important;
        }

        .mobile\:overflow-x-auto {
            overflow-x: auto !important;
        }

        .mobile\:flex-shrink-0 {
            flex-shrink: 0 !important;
        }

        .mobile\:min-w-\[800px\] {
            min-width: 800px !important;
        }

        .mobile\:max-w-\[90vw\] {
            max-width: 90vw !important;
        }

        .mobile\:w-\[95vw\] {
            width: 95vw !important;
        }

        .mobile\:rounded-xl {
            border-radius: 0.75rem !important;
        }

        .mobile\:gap-6 {
            gap: 1.5rem !important;
        }

        .mobile\:p-0\.5 {
            padding: 0.125rem !important;
        }

        .mobile\:text-4xl {
            font-size: 2.25rem !important;
        }

        .mobile\:max-h-\[90vh\] {
            max-height: 90vh !important;
        }

        .mobile\:overflow-y-auto {
            overflow-y: auto !important;
        }

        .mobile\:no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .mobile\:no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .mobile\:ml-1 {
            margin-left: 0.25rem !important;
        }
    }

    /* Animasi loading spinner */
    .animate-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    /* Style untuk notifikasi */
    #notificationContent {
        padding: 0.75rem;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        border-radius: 0.375rem;
        background: linear-gradient(135deg, #5B6541 0%, #4a4f35 100%);
    }

    /* Drag and drop untuk upload area */
    .drag-over {
        border-color: #5B6541 !important;
        background-color: rgba(91, 101, 65, 0.05) !important;
        transform: scale(1.02);
    }

    /* Smooth transitions */
    .transition-all {
        transition: all 0.3s ease-in-out;
    }

    .transition-colors {
        transition: all 0.2s ease-in-out;
    }

    /* Border dashed untuk semua form input */
    input[type="text"], textarea {
        border: none !important;
        outline: none !important;
    }

    input[type="text"]:focus, textarea:focus {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
</style>

<script>
    // Deklarasi variabel global
    let currentPopupType = '';
    let deleteLayananId = null;
    let deleteLayananName = '';
    let searchTimeout = null;

    // Fungsi untuk menampilkan notifikasi
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

        content.className = `text-white p-3 rounded-lg shadow-lg flex items-center gap-3 animate-slide-down min-w-[300px] max-w-[400px] w-auto mobile:max-w-[80vw] mobile:min-w-[260px] mobile:p-2.5 ${colors[type] || colors['default']}`;
        notificationIcon.textContent = icons[icon] || icons[type] || icons['default'];
        notificationTitle.textContent = title;
        notificationMessage.textContent = message;

        container.classList.remove('hidden');
        container.classList.add('block');

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

        if (searchInput && clearSearchBtn) {
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
                    return;
                }

                if (searchTerm.length >= 2 || searchTerm.length === 0) {
                    if (searchLoading) searchLoading.classList.remove('hidden');

                    searchTimeout = setTimeout(() => {
                        performSearch(searchTerm);
                    }, 500);
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
        }

        // Fungsi untuk melakukan search ke server
        function performSearch(searchTerm) {
            const status = new URLSearchParams(window.location.search).get('status') || '';
            const url = new URL(baseUrl);
            const params = new URLSearchParams();

            if (searchTerm) params.append('search', searchTerm);
            if (status) params.append('status', status);

            window.location.href = `${url.pathname}?${params.toString()}`;
        }
    });

    // ===== TOGGLE STATUS HANDLER =====
    document.addEventListener('DOMContentLoaded', function() {
        // Status Toggle Handler Tambah
        const statusToggle = document.getElementById('statusToggle');
        const statusValue = document.getElementById('statusValue');
        const statusText = document.getElementById('statusText');
        const statusIndicator = document.getElementById('statusIndicator');

        if (statusToggle) {
            // Set nilai awal dari old value atau default '1'
            const oldStatus = "{{ old('status', '1') }}";
            const isActive = oldStatus === '1';
            statusToggle.checked = isActive;
            if (statusValue) statusValue.value = oldStatus;

            if (statusText) {
                statusText.textContent = isActive ? 'Aktif' : 'Tidak Aktif';
            }
            if (statusIndicator) {
                statusIndicator.style.backgroundColor = isActive ? '#10B981' : '#EF4444';
            }

            statusToggle.addEventListener('change', function() {
                const isActive = statusToggle.checked;
                if (statusValue) statusValue.value = isActive ? '1' : '0';

                if (statusText) {
                    statusText.textContent = isActive ? 'Aktif' : 'Tidak Aktif';
                }
                if (statusIndicator) {
                    statusIndicator.style.backgroundColor = isActive ? '#10B981' : '#EF4444';
                }
            });
        }

        // Status Toggle Handler Edit
        const editStatusToggle = document.getElementById('editStatusToggle');
        const editStatusValue = document.getElementById('editStatusValue');
        const editStatusText = document.getElementById('editStatusText');
        const editStatusIndicator = document.getElementById('editStatusIndicator');

        if (editStatusToggle) {
            editStatusToggle.addEventListener('change', function() {
                const isActive = editStatusToggle.checked;
                if (editStatusValue) editStatusValue.value = isActive ? '1' : '0';

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
    });

    // ===== DRAG AND DROP UPLOAD =====
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('uploadArea');
        const editUploadArea = document.getElementById('editUploadArea');

        // Setup drag and drop
        [uploadArea, editUploadArea].forEach(area => {
            if (area) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    area.addEventListener(eventName, preventDefaults, false);
                });

                ['dragenter', 'dragover'].forEach(eventName => {
                    area.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    area.addEventListener(eventName, unhighlight, false);
                });

                area.addEventListener('drop', handleDrop, false);
            }
        });

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

            // Identifikasi area yang di-drop
            const areaId = e.target.id;

            if (files.length > 0) {
                const file = files[0];
                if (file.type.startsWith('image/')) {
                    if (areaId === 'uploadArea') {
                        // Foto tambah
                        const input = document.getElementById('fotoLayananInput');
                        if (input) {
                            input.files = files;
                            previewImage({ target: input });
                        }
                    } else if (areaId === 'editUploadArea') {
                        // Foto edit
                        const input = document.getElementById('editFotoLayananInput');
                        if (input) {
                            input.files = files;
                            previewEditImage({ target: input });
                        }
                    }
                } else {
                    showNotification('error', 'File tidak valid', 'Silakan unggah file gambar (JPG, PNG, GIF)', 'error');
                }
            }
        }
    });

    // ===== CLEAR ALL FILTERS =====
    function clearAllFilters() {
        window.location.href = "{{ route('layanan.index') }}";
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
    });

    // ===== FUNGSI POPUP =====
    function openPopup() {
        currentPopupType = 'add';
        const popup = document.getElementById('popupOverlay');
        if (popup) {
            popup.classList.remove('hidden');
            popup.classList.add('flex');
            document.body.style.overflow = 'hidden';
            resetForm();
        }
    }

    function closePopup() {
        const popup = document.getElementById('popupOverlay');
        if (popup) {
            popup.classList.remove('flex');
            popup.classList.add('hidden');
            document.body.style.overflow = 'auto';
            resetForm();
        }
    }

    function resetForm() {
        const form = document.getElementById('addLayananForm');
        if (form) form.reset();

        // Reset preview
        const uploadIcon = document.getElementById('uploadIcon');
        const imagePreview = document.getElementById('imagePreview');
        if (uploadIcon) uploadIcon.classList.remove('hidden');
        if (imagePreview) imagePreview.classList.add('hidden');

        // Reset status
        const statusToggle = document.getElementById('statusToggle');
        const statusValue = document.getElementById('statusValue');
        if (statusToggle) statusToggle.checked = true;
        if (statusValue) statusValue.value = '1';

        // Update UI status
        const statusText = document.getElementById('statusText');
        const statusIndicator = document.getElementById('statusIndicator');
        if (statusText) statusText.textContent = 'Aktif';
        if (statusIndicator) statusIndicator.style.backgroundColor = '#10B981';
    }

    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');
        const uploadIcon = document.getElementById('uploadIcon');

        if (input.files && input.files[0] && preview && uploadIcon) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                uploadIcon.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openEditPopup(id, namaLayanan, deskripsi, fotoLayanan, status) {
        currentPopupType = 'edit';

        // Set form action
        const form = document.getElementById('editLayananForm');
        if (form) form.action = `/layanan/${id}`;

        // Set form values
        const namaInput = document.getElementById('editNamaLayanan');
        const deskripsiInput = document.getElementById('editDeskripsiLayanan');
        if (namaInput) namaInput.value = namaLayanan || '';
        if (deskripsiInput) deskripsiInput.value = deskripsi || '';

        // Set status toggle
        const isAktif = status == '1';
        const editStatusToggle = document.getElementById('editStatusToggle');
        const editStatusValue = document.getElementById('editStatusValue');
        if (editStatusToggle) editStatusToggle.checked = isAktif;
        if (editStatusValue) editStatusValue.value = isAktif ? '1' : '0';

        // Update UI status
        const editStatusText = document.getElementById('editStatusText');
        const editStatusIndicator = document.getElementById('editStatusIndicator');
        if (editStatusText) editStatusText.textContent = isAktif ? 'Aktif' : 'Tidak Aktif';
        if (editStatusIndicator) editStatusIndicator.style.backgroundColor = isAktif ? '#10B981' : '#EF4444';

        // Set foto preview
        const preview = document.getElementById('editImagePreview');
        const uploadIcon = document.getElementById('editUploadIcon');

        if (fotoLayanan && fotoLayanan !== '' && preview && uploadIcon) {
            preview.src = fotoLayanan;
            preview.classList.remove('hidden');
            uploadIcon.classList.add('hidden');
        } else if (preview && uploadIcon) {
            preview.classList.add('hidden');
            uploadIcon.classList.remove('hidden');
        }

        // Show popup
        const popup = document.getElementById('editPopupOverlay');
        if (popup) {
            popup.classList.remove('hidden');
            popup.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeEditPopup() {
        const popup = document.getElementById('editPopupOverlay');
        if (popup) {
            popup.classList.remove('flex');
            popup.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    function previewEditImage(event) {
        const input = event.target;
        const preview = document.getElementById('editImagePreview');
        const uploadIcon = document.getElementById('editUploadIcon');

        if (input.files && input.files[0] && preview && uploadIcon) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                uploadIcon.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // ===== FUNGSI KONFIRMASI HAPUS =====
    function openDeleteConfirmation(id, namaLayanan) {
        deleteLayananId = id;
        deleteLayananName = namaLayanan;

        // Set pesan konfirmasi
        const deleteMessage = document.getElementById('deleteMessage');
        if (deleteMessage) {
            deleteMessage.textContent = `Apakah Anda yakin ingin menghapus layanan "${namaLayanan}"?`;
        }

        // Set action form delete
        const deleteForm = document.getElementById('deleteForm');
        if (deleteForm) deleteForm.action = `/layanan/${id}`;

        // Tampilkan popup konfirmasi
        const overlay = document.getElementById('deleteConfirmationOverlay');
        if (overlay) {
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeDeleteConfirmation() {
        const overlay = document.getElementById('deleteConfirmationOverlay');
        if (overlay) {
            overlay.classList.remove('flex');
            overlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
            deleteLayananId = null;
            deleteLayananName = '';
        }
    }

    // ===== FUNGSI KONFIRMASI BATAL =====
    function openCancelConfirmation(type) {
        currentPopupType = type;
        const overlay = document.getElementById('cancelConfirmationOverlay');
        if (overlay) {
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeCancelConfirmation() {
        const overlay = document.getElementById('cancelConfirmationOverlay');
        if (overlay) {
            overlay.classList.remove('flex');
            overlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
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

    // Event listeners untuk close popup
    document.addEventListener('DOMContentLoaded', function() {
        // Close popup when clicking outside
        const popupOverlay = document.getElementById('popupOverlay');
        const editPopupOverlay = document.getElementById('editPopupOverlay');
        const deleteOverlay = document.getElementById('deleteConfirmationOverlay');
        const cancelOverlay = document.getElementById('cancelConfirmationOverlay');

        if (popupOverlay) {
            popupOverlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    openCancelConfirmation('add');
                }
            });
        }

        if (editPopupOverlay) {
            editPopupOverlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    openCancelConfirmation('edit');
                }
            });
        }

        if (deleteOverlay) {
            deleteOverlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteConfirmation();
                }
            });
        }

        if (cancelOverlay) {
            cancelOverlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeCancelConfirmation();
                }
            });
        }

        // Close popup with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (deleteOverlay && !deleteOverlay.classList.contains('hidden')) {
                    closeDeleteConfirmation();
                } else if (cancelOverlay && !cancelOverlay.classList.contains('hidden')) {
                    closeCancelConfirmation();
                } else if (popupOverlay && !popupOverlay.classList.contains('hidden')) {
                    openCancelConfirmation('add');
                } else if (editPopupOverlay && !editPopupOverlay.classList.contains('hidden')) {
                    openCancelConfirmation('edit');
                }
            }
        });
    });

    // Open popup if there are validation errors
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
