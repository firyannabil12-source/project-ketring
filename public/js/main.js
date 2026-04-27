// Add to Cart Interactivity
function addToCart(event, itemName) {
    alert(`Berhasil menambahkan "${itemName}" ke keranjang belanja!`);
    
    // Smooth animation for button
    const btn = event.currentTarget;
    const originalText = btn.innerText;
    btn.innerText = '✅ Berhasil!';
    btn.style.backgroundColor = '#2E7D32';
    
    setTimeout(() => {
        btn.innerText = originalText;
        btn.style.backgroundColor = '';
    }, 2000);
}

document.addEventListener('DOMContentLoaded', () => {
    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.style.boxShadow = 'var(--shadow-md)';
            navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
        } else {
            navbar.style.boxShadow = 'var(--shadow-sm)';
            navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
        }
    });

    // Simple Menu Filter Logic
    const filterButtons = document.querySelectorAll('.btn[style*="background: white"]');
    const allButton = document.querySelector('.btn-primary'); // Assuming first primary btn is 'All'
    
    if (filterButtons.length > 0) {
        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const category = btn.textContent.trim().toUpperCase();
                console.log(`Filtering for: ${category}`);
                
                // Toggle active state
                filterButtons.forEach(b => {
                    b.style.backgroundColor = 'white';
                    b.style.color = 'var(--secondary)';
                });
                btn.style.backgroundColor = 'var(--primary)';
                btn.style.color = 'white';
                
                // Show/Hide cards
                const cards = document.querySelectorAll('.food-card');
                cards.forEach(card => {
                    const cardCategory = card.querySelector('span')?.textContent.trim();
                    if (cardCategory === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }
});
