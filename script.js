// Minimal JavaScript for smooth scrolling
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80, // Adjust for navbar height
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Enhanced "Add to Cart" functionality
    const addToCartButtons = document.querySelectorAll('.btn-add-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const formData = new FormData(form);
            
            // Disable button and show loading state
            this.disabled = true;
            const originalText = this.innerHTML;
            this.innerHTML = 'Adding...';
            
            // Send AJAX request
            // fetch('add_to_cart.php', {
            //     method: 'POST',
            //     body: formData
            // })
            // .then(response => response.json())
            // .then(data => {
            //     if (data.success) {
            //         // Update cart count in navigation
            //         const cartCountElement = document.querySelector('.cart-count');
            //         if (cartCountElement) {
            //             cartCountElement.textContent = data.cart_count;
            //         }
                    
            //         // Show success message
            //         this.innerHTML = 'Added to Cart ✓';
            //         this.style.backgroundColor = '#4CAF50';
                    
            //         // Reset button after 2 seconds
            //         setTimeout(() => {
            //             this.innerHTML = originalText;
            //             this.style.backgroundColor = '';
            //             this.disabled = false;
            //         }, 2000);
            //     } else {
            //         // Show error message
            //         this.innerHTML = 'Error: ' + data.message;
            //         this.style.backgroundColor = '#f44336';
                    
            //         // Reset button after 2 seconds
            //         setTimeout(() => {
            //             this.innerHTML = originalText;
            //             this.style.backgroundColor = '';
            //             this.disabled = false;
            //         }, 2000);
            //     }
            // })
            // .catch(error => {
            //     console.error('Error:', error);
            //     this.innerHTML = 'Error adding to cart';
            //     this.style.backgroundColor = '#f44336';
                
            //     // Reset button after 2 seconds
            //     setTimeout(() => {
            //         this.innerHTML = originalText;
            //         this.style.backgroundColor = '';
            //         this.disabled = false;
            //     }, 2000);
            // });
        });
    });
});