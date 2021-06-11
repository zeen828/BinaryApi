<?php

namespace App\Admin\Controllers\Website;

use App\Models\Website\Website;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WebsiteController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Website';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Website());

        $grid->column('id', __('field.id'));
        $grid->column('title', __('Title'));
        $grid->column('description', __('Description'));
        $grid->column('keyword', __('Keyword'));
        $grid->column('ga_key', __('Ga key'));
        $grid->column('domain', __('Domain'));
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
        $show = new Show(Website::findOrFail($id));

        $show->field('id', __('field.id'));
        $show->field('title', __('Title'));
        $show->field('description', __('Description'));
        $show->field('keyword', __('Keyword'));
        $show->field('ga_key', __('Ga key'));
        $show->field('domain', __('Domain'));
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
        $form = new Form(new Website());

        $form->text('title', __('Title'))->default('網站');
        $form->textarea('description', __('Description'));
        $form->textarea('keyword', __('Keyword'));
        $form->text('ga_key', __('Ga key'));
        $form->text('domain', __('Domain'));
        $form->switch('status', __('field.status'));

        return $form;
    }
}
