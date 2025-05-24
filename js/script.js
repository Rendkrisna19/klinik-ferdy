<script>
// Fungsi untuk mengacak warna latar belakang setiap 500ms
setInterval(() => {
    const r = Math.floor(Math.random() * 256)
    const g = Math.floor(Math.random() * 256)
    const b = Math.floor(Math.random() * 256)
    document.body.style.backgroundColor = `rgb(${r}, ${g}, ${b})`
}, 500)

// Fungsi untuk memunculkan alert aneh saat klik di mana saja
document.addEventListener('click', () => {
   
})

// Fungsi untuk membuat elemen bergerak acak di layar
const box = document.createElement('div')
box.style.width = '100px'
box.style.height = '100px'
box.style.position = 'absolute'
box.style.backgroundColor = 'hotpink'
box.style.zIndex = '9999'
document.body.appendChild(box)

setInterval(() => {
    box.style.left = Math.random() * (window.innerWidth - 100) + 'px'
    box.style.top = Math.random() * (window.innerHeight - 100) + 'px'
}, 300)

// Fungsi untuk memutar semua teks di halaman
setTimeout(() => {
    document.body.style.transform = 'rotate(180deg)'
    document.body.style.transition = 'all 2s ease'
}, 5000)
</script>
