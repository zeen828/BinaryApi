<?php

namespace App\Admin\Controllers\Binary;

use App\Models\Binary\Binary;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BinaryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Binary';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Binary());
        $grid->column('id', __('field.id'));
        $grid->column('name', __('binary.field.name'));
        $grid->column('code', __('binary.field.code'));
        $grid->column('logo', __('binary.field.logo'));
        // $grid->column('description', __('binary.field.'));
        // $grid->column('description_zh', __('binary.field.'));
        $grid->column('website', __('binary.field.description'));
        $grid->column('currency', __('binary.field.description_zh'));
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
        $show = new Show(Binary::findOrFail($id));

        $show->field('id', __('field.id'));
        $show->field('name', __('Name'));
        $show->field('code', __('Code'));
        $show->field('logo', __('Logo'));
        $show->field('description', __('Description'));
        $show->field('description_zh', __('Description zh'));
        $show->field('website', __('Website'));
        $show->field('currency', __('Currency'));
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
        $form = new Form(new Binary());

        $form->text('name', __('Name'));
        $form->text('code', __('Code'));
        $form->text('logo', __('Logo'));
        $form->textarea('description', __('Description'));
        $form->textarea('description_zh', __('Description zh'));
        $form->text('website', __('Website'));
        $form->textarea('currency', __('Currency'));
        $form->switch('status', __('field.status'));

        return $form;
    }
}
