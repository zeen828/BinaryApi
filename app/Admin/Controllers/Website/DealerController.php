<?php

namespace App\Admin\Controllers\Website;

use App\Models\Website\Dealer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DealerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Dealer';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Dealer());

        $grid->column('iv', __('Iv'));
        $grid->column('name', __('Name'));
        $grid->column('key', __('Key'));
        $grid->column('ip', __('Ip'));
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
        $show = new Show(Dealer::findOrFail($id));

        $show->field('iv', __('Iv'));
        $show->field('name', __('Name'));
        $show->field('key', __('Key'));
        $show->field('ip', __('Ip'));
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
        $form = new Form(new Dealer());

        $form->text('iv', __('Iv'));
        $form->text('name', __('Name'));
        $form->text('key', __('Key'));
        $form->ip('ip', __('Ip'));
        $form->switch('status', __('field.status'));

        return $form;
    }
}
