<?php
$alpineComponent ??= 'crudTable';

$this->pushJs('components/crudTable/crudTable.js');
?>

<div
    x-data="<?= $alpineComponent ?>"
    x-ref="table"
    @form-success.window="handleFormSuccess($event.detail)"
    class="CrudTable"></div>

<style>
    .CrudTable {
        overflow: auto;

        .crud-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 3px;

            button {
                width: 50px;
            }
        }

        .gridjs-head {
            display: flex;
            justify-content: space-between;
            flex-direction: row-reverse;

            &::after {
                content: unset;
            }

            button {
                background-color: var(--bs-btn-bg);
                color: var(--bs-btn-color);
                padding: var(--bs-btn-padding-y) var(--bs-btn-padding-x);
                width: 50px;

                &:hover {
                    background-color: var(--bs-btn-hover-bg);
                }
            }
        }

        .gridjs-wrapper {
            box-shadow: unset;
        }

        .gridjs-footer {
            box-shadow: unset;
        }
    }
</style>