<?php

namespace App\Admin\Controllers\User;

use App\Models\User\UserBetting;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BettingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'UserBetting';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserBetting());

        $grid->column('id', __('field.id'));
        $grid->column('rUser.name', __('User id'));
        $grid->column('rCurrency.binary_name', __('Binary currency id'));
        $grid->column('rCurrencyTrend.period', __('Binary currency trend id'));
        $grid->column('rRuleCurrency.name', __('Binary rule currency id'));
        $grid->column('binary_rule_currency_value', __('Binary rule currency value'));
        $grid->column('quantity', __('Quantity'));
        $grid->column('amount', __('Amount'));
        $grid->column('profit', __('Profit'));
        $grid->column('win_sys', __('Win sys'))->using([0=>'未開',1=>'贏',-1=>'輸',], 'Unknown')->dot([0=>'info',1=>'danger',-1=>'success',], 'warning');
        $grid->column('win_user', __('Win user'))->using([0=>'未開',1=>'贏',-1=>'輸',], 'Unknown')->dot([0=>'info',1=>'danger',-1=>'success',], 'warning');
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
        $show = new Show(UserBetting::findOrFail($id));

        $show->field('id', __('field.id'));
        $show->field('user_id', __('User id'));
        $show->field('binary_currency_id', __('Binary currency id'));
        $show->field('binary_currency_trend_id', __('Binary currency trend id'));
        $show->field('binary_rule_currency_id', __('Binary rule currency id'));
        $show->field('binary_rule_currency_value', __('Binary rule currency value'));
        $show->field('quantity', __('Quantity'));
        $show->field('amount', __('Amount'));
        $show->field('profit', __('Profit'));
        $show->field('win_sys', __('Win sys'));
        $show->field('win_user', __('Win user'));
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
        $form = new Form(new UserBetting());

        $form->number('user_id', __('User id'));
        $form->number('binary_currency_id', __('Binary currency id'));
        $form->number('binary_currency_trend_id', __('Binary currency trend id'));
        $form->number('binary_rule_currency_id', __('Binary rule currency id'));
        $form->text('binary_rule_currency_value', __('Binary rule currency value'));
        $form->number('quantity', __('Quantity'));
        $form->decimal('amount', __('Amount'));
        $form->decimal('profit', __('Profit'));
        $form->switch('win_sys', __('Win sys'));
        $form->switch('win_user', __('Win user'));
        $form->switch('status', __('field.status'));

        return $form;
    }
}
