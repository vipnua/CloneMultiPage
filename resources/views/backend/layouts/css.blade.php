<style>
    .dataTables_empty {
        text-align: center;
    }

    .dashboard-page {
        margin: 2rem 3rem;
        height: 100%;
    }

    .daterangepicker_dashboard-group {
        max-width: max-content;
    }

    .dashboard-group {
        display: flex;
        justify-content: space-evenly;
        margin-top: 2%;
        gap: 2%;
    }

    .dashboard-item {
        display: flex;
        background: #fff;
        flex-direction: column;
        align-items: center;
        width: 25%;
        min-width: 200px;
        height: 160px;
        border-radius: 10px;
        box-shadow: 0 0 5px #dfdfdf;
        justify-content: center;
    }

    .dashboard-item .dashboard-item_title {
        font-size: 1.6rem;
        font-weight: 600;
        color: #a1a0a0;
    }

    .dashboard-item .dashboard-item_number {
        font-size: 3rem;
        font-weight: 600;
        color: #888;
    }

    .dashboard-item:hover {
        box-shadow: 0 0 5px var(--bs-blue);
        transition: all 0.2s linear 0.2s;
        cursor: pointer;
    }

    .dashboard-item:hover .dashboard-item_title {
        color: var(--bs-blue);
        opacity: 0.6;
        text-shadow: 0 0 1px var(--bs-blue);
        transition: all 0.2s linear 0.3s;
    }

    .dashboard-item:hover .dashboard-item_number {
        color: var(--bs-blue);
        opacity: 0.6;
        text-shadow: 0 0 1px var(--bs-blue);
        transition: all 0.2s linear 0.3s;
    }

    .toast.success {
        border: 1px var(--bs-active-success) solid;
    }

    .toast.error {
        border: 1px var(--bs-active-danger) solid;
    }
    .hiden-text-30vw{
        max-width: 30vw;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }
    .tooltip-inner {
        max-width: 1000px;
        /* If max-width does not work, try using width instead */
        /*width: 350px;*/
    }
</style>
