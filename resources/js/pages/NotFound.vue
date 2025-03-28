<!-- src/views/NotFoundView.vue -->
<template>
    <div class="min-h-screen flex items-center justify-center">
      <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">404</h1>
        <p class="text-xl text-gray-600">Halaman tidak ditemukan</p>
        <router-link
          to="/"
          class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
        >
          Kembali ke Beranda
        </router-link>
      </div>
    </div>
  </template>
