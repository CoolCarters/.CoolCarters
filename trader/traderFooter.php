<!-- Footer -->
<footer id="footer">
    <p>&copy; 2025 CoolCarter. All rights reserved</p>
    <div class="socials">
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fas fa-times"></i></a>
    </div>
</footer>

<!-- Styles for Footer -->
<style>
    :root {
        --primary: #4e73df;
        --secondary: #2e59d9;
        --light: #f8f9fc;
        --dark: #343a40;
        --text: #858796;
        --bg: #fff;
        --sidebar-width: 240px;
        --collapsed-width: 70px;
    }

    footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 60px;
        background: var(--bg);
        box-shadow: 0 -2px 4px rgba(0, 0, 0, .05);
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 2rem 0 calc(var(--sidebar-width) + 2rem);
        transition: padding-left 0.3s ease;
    }

    body.sidebar-collapsed footer {
        padding-left: calc(var(--collapsed-width) + 2rem);
    }

    .socials a {
        font-size: 1.2rem;
        color: var(--text);
        margin-left: 1rem;
        transition: color .2s;
    }

    .socials a:hover {
        color: var(--primary);
    }

    footer p {
        font-size: .85rem;
        color: var(--text);
    }

    @media (max-width: 768px) {
        footer {
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: auto;
            padding: 1rem;
            text-align: center;
        }

        body.sidebar-collapsed footer {
            padding-left: 1rem;
        }

        .socials {
            margin-top: 0.5rem;
        }
    }
</style>

<!-- Toggle Sidebar Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleBtn');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            document.body.classList.toggle('sidebar-collapsed');
        });
    }
});
</script>
