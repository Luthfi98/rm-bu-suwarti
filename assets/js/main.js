// Intersection Observer for scroll animations
const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
};

const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Observe elements that need animation
document.addEventListener('DOMContentLoaded', () => {
    // About section
    const aboutRow = document.querySelector('.about .row');
    if (aboutRow) observer.observe(aboutRow);

    // Menu cards
    const menuCards = document.querySelectorAll('.menu-card');
    menuCards.forEach(card => observer.observe(card));

    // Contact section
    const contactRow = document.querySelector('.contact .row');
    if (contactRow) observer.observe(contactRow);

    // Contact form inputs
    const formInputs = document.querySelectorAll('.contact .row form .input-group');
    formInputs.forEach((input, index) => {
        setTimeout(() => {
            observer.observe(input);
        }, index * 200); // Stagger the animations
    });

    // Menu Filtering
    const tabButtons = document.querySelectorAll('.tab-btn');

    // Function to filter menu items
    function filterMenu(category) {
        menuCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category');
            
            if (category === 'all' || cardCategory === category) {
                card.classList.remove('hide');
                // Add animation delay based on index
                const index = Array.from(menuCards).indexOf(card);
                setTimeout(() => {
                    card.classList.add('visible');
                }, index * 100);
            } else {
                card.classList.add('hide');
                card.classList.remove('visible');
            }
        });
    }

    // Add click event to tab buttons
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons
            tabButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            button.classList.add('active');
            // Filter menu items
            const category = button.getAttribute('data-category');
            filterMenu(category);
        });
    });

    // Initial filter for all items
    filterMenu('all');
});

// Smooth scroll for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add active class to navigation links on scroll
window.addEventListener('scroll', () => {
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.navbar-nav a');
    
    let current = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (pageYOffset >= sectionTop - 60) {
            current = section.getAttribute('id');
        }
    });

    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href').substring(1) === current) {
            link.classList.add('active');
        }
    });
}); 