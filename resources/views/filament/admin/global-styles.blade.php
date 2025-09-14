<style>
    /* Add ADMIN badge after logo */
    .fi-sidebar-header::after {
        content: 'ADMIN';
        display: block;
        background: #dc2626;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        margin-top: 8px;
        width: fit-content;
        letter-spacing: 0.5px;
    }

    /* Make logo bigger */
    .fi-sidebar-header img {
        height: 3.5rem !important;
        width: auto !important;
    }

    /* Adjust header spacing */
    .fi-sidebar-header {
        padding: 1rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 0.5rem;
    }

    /* Style the brand name */
    .fi-sidebar-header .text-xl {
        font-size: 0.875rem !important;
        color: #6b7280;
        margin-top: 0.5rem;
    }
</style>