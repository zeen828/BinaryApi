<?php

namespace App\Admin\Controllers\Order;

use App\Models\Order\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BetController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Order';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());
        $grid->model()->where('event', 'score');

        $grid->column('id', __('field.id'));
        $grid->column('sn', __('Sn'));
        $grid->column('user_id', __('User id'));
        $grid->column('order_sn', __('Order sn'));
        $grid->column('event', __('Event'));
        $grid->column('point', __('Point'));
        $grid->column('remarks', __('Remarks'));
        $grid->column('status', __('field.status'));
        $grid->column('created_at', __('field.created_at'));
        $grid->column('updated_at', __('field.updated_at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('field.id'));
        $show->field('sn', __('Sn'));
        $show->field('user_id', __('User id'));
        $show->field('order_sn', __('Order sn'));
        $show->field('event', __('Event'));
        $show->field('point', __('Point'));
        $show->field('remarks', __('Remarks'));
        $show->field('status', __('field.status'));
        $show->field('created_at', __('field.created_at'));
        $show->field('updated_at', __('field.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order());

        $form->text('sn', __('Sn'));
        $form->number('user_id', __('User id'));
        $form->text('order_sn', __('Order sn'));
        $form->text('event', __('Event'))->default('billing');
        $form->number('point', __('Point'));
        $form->textarea('remarks', __('Remarks'));
        $form->switch('status', __('field.status'));

        return $form;
    }
}
