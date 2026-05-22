<?php
$cardTitle = $cardTitle ?? "";
$children = $children ?? "";
?>

<div class="CardComponent">
    <div class="card">
        <header class="card-header">
            <h1 class="card-title">
                <i class="fas fa-dumbbell"></i>
                <?= $cardTitle ?>
            </h1>
        </header>

        <div class="card-body">
            <?= $children ?>
        </div>
    </div>
</div>

<style>
    .CardComponent {
        >.card {
            --bs-gutter-x: 0;
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            border-radius: 28px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card>.card-header {
            background: #C62828;
            color: white;
            padding: 1.2rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;

            h1 {
                font-size: 1.6rem;
                font-weight: 600;
                margin: 0;
            }
        }
    }
</style>