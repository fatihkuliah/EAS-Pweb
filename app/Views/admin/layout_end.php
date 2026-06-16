        </div>
    </div>
</div>

<script>
    const toggleBtn = document.getElementById('mobile-sidebar-toggle');
    const sidebar = document.querySelector('.admin-sidebar');
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (sidebar.style.display === 'none' || sidebar.style.display === '') {
                sidebar.style.setProperty('display', 'flex', 'important');
                sidebar.style.position = 'fixed';
                sidebar.style.top = '0';
                sidebar.style.left = '0';
                sidebar.style.zIndex = '1050';
                sidebar.style.height = '100vh';
            } else {
                sidebar.style.setProperty('display', 'none', 'important');
            }
        });
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 768 && !sidebar.contains(e.target) && e.target !== toggleBtn) {
                sidebar.style.setProperty('display', 'none', 'important');
            }
        });
    }
</script>

<style>
    @media (max-width: 767.98px) {
        .admin-sidebar {
            display: none !important;
            position: fixed !important;
            top: 0;
            left: 0;
            height: 100vh !important;
            z-index: 1050;
            box-shadow: 5px 0px 20px rgba(0, 0, 0, 0.4);
        }
    }
    
    .table-brutalist {
        background-color: #ffeed3 !important;
        border: 2px solid #000 !important;
        border-radius: 15px;
        overflow: hidden;
    }
    .table-brutalist th {
        background-color: #ff9533 !important;
        color: #000 !important;
        font-weight: 800;
        border-bottom: 2px solid #000 !important;
        border-right: 2px solid #000 !important;
        padding: 12px !important;
    }
    .table-brutalist td {
        background-color: #ffeed3 !important;
        color: #000 !important;
        font-weight: 600;
        border-bottom: 2px solid #000 !important;
        border-right: 2px solid #000 !important;
        padding: 12px !important;
        vertical-align: middle;
    }
    .table-brutalist tr:last-child td {
        border-bottom: none !important;
    }
    .table-brutalist th:last-child, .table-brutalist td:last-child {
        border-right: none !important;
    }
    
    .card-brutalist {
        background-color: #ffeed3;
        border: 2px solid #000;
        border-radius: 20px;
        box-shadow: 4px 4px 0px #000;
        color: #000;
        padding: 20px;
    }
    
    .btn-brutalist {
        border: 2px solid #000;
        border-radius: 50px;
        font-weight: 700;
        padding: 6px 16px;
        font-size: 14px;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-brutalist-primary {
        background-color: #ff9533;
        color: #000;
    }
    .btn-brutalist-primary:hover {
        background-color: #ffa439;
        color: #000;
        transform: translate(-1px, -1px);
    }
    .btn-brutalist-secondary {
        background-color: #ffc38b;
        color: #000;
    }
    .btn-brutalist-secondary:hover {
        background-color: #ffd2a8;
        color: #000;
        transform: translate(-1px, -1px);
    }
    .btn-brutalist-danger {
        background-color: #ff6b6b;
        color: #fff;
    }
    .btn-brutalist-danger:hover {
        background-color: #ff8585;
        color: #fff;
        transform: translate(-1px, -1px);
    }
    
    .badge-brutalist {
        border: 2px solid #000;
        border-radius: 50px;
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 700;
        color: #000;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .form-control-brutalist, .form-select-brutalist {
        border: 2px solid #000 !important;
        border-radius: 10px !important;
        background-color: #ffeed3 !important;
        color: #000 !important;
        font-weight: 600 !important;
        padding: 8px 12px !important;
    }
    .form-control-brutalist:focus, .form-select-brutalist:focus {
        background-color: #ffeed3 !important;
        border-color: #ff9533 !important;
        box-shadow: none !important;
        color: #000 !important;
    }
</style>
