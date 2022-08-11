<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="m-content">
	<div class="m-portlet m-portlet--mobile">
		<div class="m-portlet__body">
			<!--begin: Search Form -->
			<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
				<div class="row align-items-center">
					<div class="col-xl-8 order-2 order-xl-1">
						<div class="form-group m-form__group row align-items-center">
							<div class="col-md-8">
								<div class="m-input-icon m-input-icon--left">
									<input type="text" class="form-control m-input" placeholder="Search..." id="generalSearch">
									<span class="m-input-icon__icon m-input-icon__icon--left">
										<span>
											<i class="la la-search"></i>
										</span>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 order-1 order-xl-2 m--align-right">
						<?php echo button_admin(['add', 'delete']) ?>
						<div class="m-separator m-separator--dashed d-xl-none"></div>
					</div>
				</div>
			</div>
			<!--end: Search Form -->
			<!--begin: Datatable -->
			<div class="m_datatable" id="ajax_data"></div>
			<!--end: Datatable -->
		</div>
	</div>
</div>

<div class="modal fade" id="modal_form" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="formModalLabel">Form</h3>
			</div>
			<div class="modal-body">
				<?php echo form_open('', ['id' => '', 'class' => 'm-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed m-form--state']) ?>
				<input type="hidden" name="id" value="0">
				<div class="m-portlet--tabs">
					<div class="m-portlet__body">
						<div class="tab-content">
							<div class="row">
								<div class="col-sm-6 col-xs-12">
									<div class="form-group">
										<label>Tiêu đề</label>
										<input name="title" placeholder="Tiêu đề" class="form-control" type="text" />
									</div>
									<div class="form-group">
										<label>Vị trí</label>
										<div class="m-input">
											<select class="form-control m-input m-input--square" name="location">
												<?php if (!empty($this->config->item('cms_banner'))) foreach ($this->config->item('cms_banner') as $position => $name) : ?>
													<option value="<?php echo $position ?>"><?php echo $name; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label>Trạng thái:</label>
										<div class="m-input">
											<input data-switch="true" type="checkbox" name="is_status" class="switchBootstrap" checked="checked">
										</div>
									</div>

									<div class="form-group">
										<label>Thiết bị:</label>
										<div class="m-input">
											<select class="form-control m-input m-input--square" name="is_device">
												<option value="0">Cả Mobile & PC</option>
												<option value="1">Chỉ Mobile</option>
												<option value="2">Chỉ PC</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-xs-12">
									<div class="form-group">
										<label>Thứ tự</label>
										<input name="order" value="1" class="form-control" type="text" />
									</div>
									<div class="form-group">
										<label>Link</label>
										<input name="data_info[link]" placeholder="Link" class="form-control" type="text" />
									</div>
									<div class="form-group">
										<label>Title Link</label>
										<input name="data_info[title]" placeholder="Title Link" class="form-control" type="text" />
									</div>
									<div class="form-group">
										<label>Alt image</label>
										<input name="data_info[alt]" placeholder="Alt image" class="form-control" type="text" />
									</div>
									<div class="form-group">
										<label for="thumbnail">Ảnh</label>
										<div class="input-group m-input-group m-input-group--air">
											<div class="input-group-prepend">
												<span class="input-group-text" id="input_thumbnail">
													<i class="la la-picture-o"></i>
												</span>
											</div>
											<input type="text" name="thumbnail" onclick="FUNC.chooseImage(this)" class="form-control m-input chooseImage" placeholder="Click để chọn ảnh" aria-describedby="input_thumbnail">
										</div>
										<div class="alert m-alert m-alert--default preview text-center mt-1" role="alert">
											<img width="100" height="100" src="<?php echo getImageThumb('', 100, 100) ?>">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php echo form_close() ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary btnSave">Submit</button>
			</div>
		</div>
	</div>
</div>