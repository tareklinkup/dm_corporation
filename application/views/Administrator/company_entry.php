<style>
    .v-select{
		margin-bottom: 5px;
	}
	.v-select.open .dropdown-toggle{
		border-bottom: 1px solid #ccc;
	}
	.v-select .dropdown-toggle{
		padding: 0px;
		height: 25px;
	}
	.v-select input[type=search], .v-select input[type=search]:focus{
		margin: 0px;
	}
	.v-select .vs__selected-options{
		overflow: hidden;
		flex-wrap:nowrap;
	}
	.v-select .selected-tag{
		margin: 2px 0px;
		white-space: nowrap;
		position:absolute;
		left: 0px;
	}
	.v-select .vs__actions{
		margin-top:-5px;
	}
	.v-select .dropdown-menu{
		width: auto;
		overflow-y:auto;
	}
</style>
<div id="companies">
    <div class="row" style="margin-top: 15px;">
        <div class="col-md-8">
            <form class="form-horizontal" @submit.prevent="addCompany">                
				
				<div class="form-group">
                    <label class="col-sm-6 control-label no-padding-right"> Company Name </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-5">
                        <input type="text" placeholder="Company Name" class="form-control" v-model="company.Company_Name" required />
                    </div>
				</div>

                <div class="form-group">
                    <label class="col-sm-6 control-label no-padding-right"></label>
                    <label class="col-sm-1 control-label no-padding-right"></label>
                    <div class="col-sm-5">
                        <button type="submit" class="btn btn-sm btn-success">
                            Save
                            <i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-4">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 form-inline">
            <div class="form-group">
                <label for="filter" class="sr-only">Filter</label>
                <input type="text" class="form-control" v-model="filter" placeholder="Filter">
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <datatable :columns="columns" :data="companies" :filter-by="filter">
                    <template scope="{ row }">
                        <tr>
                            <td>{{ row.Company_Name }}</td>
                            <td>
                                <?php if($this->session->userdata('accountType') != 'u'){?>
                                <button type="button" class="button edit" @click="editCompany(row)">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button type="button" class="button" @click="deleteCompany(row.Company_SlNo)">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <?php }?>
                            </td>
                        </tr>
                    </template>
                </datatable>
                <datatable-pager v-model="page" type="abbreviated" :per-page="per_page"></datatable-pager>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#companies',
        data(){
            return {
                company: {
                    Company_SlNo: 0,
                    Company_Name: '',
                },
                companies: [],
				columns: [
                    { label: 'Company Name', field: 'Company_Name', align: 'center'},
                    { label: 'Action', align: 'center', filterable: false }
                ],
                page: 1,
                per_page: 10,
                filter: ''
            }
        },
        created(){
            this.getCompanies();
        },
        methods: {
			addCompany(){
				
                let url = '/add_company';
                if(this.company.Company_SlNo != 0){
                    url = '/update_company'
                }
				axios.post(url, this.company).then(res => {
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.resetForm();
                        this.getCompanies();
					}
				})
			},

            editCompany(company){
                let keys = Object.keys(this.company);
                keys.forEach(key => this.company[key] = company[key]);
            },

            deleteCompany(companyId){
                let deleteConfirm = confirm('Are you sure?');
                if(deleteConfirm == false){
                    return;
                }
                axios.post('/delete_company', {companyId: companyId}).then(res => {
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.resetForm();
                        this.getCompanies();
					}
				})
            },

            getCompanies(){
                axios.get('/get_companies').then(res => {
                    this.companies = res.data;
                })
            },

			resetForm(){
				this.company.Company_SlNo = 0;
				this.company.Company_Name = '';
			}
        }
    })
</script>
