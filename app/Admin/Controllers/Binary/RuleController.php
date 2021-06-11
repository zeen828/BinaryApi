<?php

namespace App\Admin\Controllers\Binary;

use App\Models\Binary\BinaryRuleCurrency;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RuleController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'BinaryRuleCurrency';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BinaryRuleCurrency());

        $grid->column('id', __('field.id'));
        $grid->column('binary_currency_id', __('Binary currency id'));
        $grid->column('binary_rule_type_id', __('Binary rule type id'));
        $grid->column('name', __('Name'));
        $grid->column('rule_json', __('Rule json'));
        $grid->column('bet_json', __('Bet json'));
        $grid->column('odds', __('Odds'));
        $grid->column('sort', __('Sort'));
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
        $show = new Show(BinaryRuleCurrency::findOrFail($id));

        $show->field('id', __('field.id'));
        $show->field('binary_currency_id', __('Binary currency id'));
        $show->field('binary_rule_type_id', __('Binary rule type id'));
        $show->field('name', __('Name'));
        $show->field('rule_json', __('Rule json'));
        $show->field('bet_json', __('Bet json'));
        $show->field('odds', __('Odds'));
        $show->field('sort', __('Sort'));
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
        $form = new Form(new BinaryRuleCurrency());

        $form->number('binary_currency_id', __('Binary currency id'));
        $form->number('binary_rule_type_id', __('Binary rule type id'));
        $form->text('name', __('Name'));
        $form->textarea('rule_json', __('Rule json'));
        $form->textarea('bet_json', __('Bet json'));
        $form->decimal('odds', __('Odds'))->default(1.000);
        $form->switch('sort', __('Sort'));
        $form->switch('status', __('field.status'));

        return $form;
    }
}
