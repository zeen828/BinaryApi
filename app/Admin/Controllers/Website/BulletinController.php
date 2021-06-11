<?php

namespace App\Admin\Controllers\Website;

use App\Models\Website\Bulletin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BulletinController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Bulletin';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Bulletin());

        $grid->column('id', __('field.id'));
        $grid->column('title', __('Title'));
        $grid->column('content', __('Content'));
        $grid->column('img', __('Img'));
        $grid->column('start_at', __('Start at'));
        $grid->column('end_at', __('End at'));
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
        $show = new Show(Bulletin::findOrFail($id));

        $show->field('id', __('field.id'));
        $show->field('title', __('Title'));
        $show->field('content', __('Content'));
        $show->field('img', __('Img'));
        $show->field('start_at', __('Start at'));
        $show->field('end_at', __('End at'));
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
        $form = new Form(new Bulletin());

        $form->text('title', __('Title'));
        $form->textarea('content', __('Content'));
        $form->image('img', __('Img'));
        $form->datetime('start_at', __('Start at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('end_at', __('End at'))->default(date('Y-m-d H:i:s'));
        $form->switch('status', __('field.status'));

        return $form;
    }
}
