<template>
    <div id="right">
        <h1>Development CRM</h1>
        
        <div class="horizontal">
            <img src="images/horizontal.png">
        </div>

        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. 
            Tempora incidunt, harum hic rem, quia laudantium in 
            sunt odio distinctio numquam iusto illo expedita fugit 
            suscipit maiores molestias commodi voluptatum pariatur?
        </p>

        <div class="task">
            <div class="add-tasks">
                <h2>Today's Task</h2>

                <div class="add-action">
                    <img src="images/add.png">
                </div>
            </div>

            <ul class="tasks-list">
                <li v-for="task in todaytasks" v-bind:key="task.id">
                    <div class="info">
                        <div class="left">
                            <label class="myCheckbox">
                                <input type="checkbox" name="test" 
                                :checked="task.completed"
                                @change="updateTodayTask(task.taskId)"
                                />
                                <span></span>
                            </label>
                            <h4>{{ task.title }}</h4>
                        </div>
                        <div class="right">
                            <img src="images/edit.png">
                            <img src="images/del.png" @click="delTodayTask(task.taskId)">
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div class="upcoming">
            <div class="add-task">
                <h2>Upcoming</h2>
                <div class="add-action">
                    <img src="images/add.png">
                </div>
                <form action="" @submit="addUpcomingTask">
                    <input type="text" v-model="newTask" />
                </form>

                <ul class="tasks-list">
                    <li v-for="upcomingtask in upcoming" v-bind:key="upcomingtask.id">
                        <div class="info">
                            <div class="left">
                                <label class="myCheckbox">
                                    <input type="checkbox" name="test" 
                                    :checked="upcomingtask.completed"
                                    @change="checkUpcoming(upcomingtask.taskId)"
                                    />
                                    <span></span>
                                </label>
                                <h4>{{ upcomingtask.title }}</h4>
                            </div>
                            <div class="right">
                                <img src="images/edit.png">
                                <img src="images/del.png" @click="delUpcoming(upcomingtask.taskId)">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                todaytasks: [],
                upcoming: [],
                newTask: "",
            };
        },

        created(){
            this.fetchTodayTasks();
            this.fetchUpcoming();
        },

        methods: {
                // ** Upcoming Task ** //
            // Get upcoming tasks
            fetchUpcoming() {
                fetch('/api/upcoming')
                .then(res => res.json())
                .then(({data}) => {
                    this.upcoming = data;
                    console.log(data);
                })
                .catch((err) => console.log(err))
            },

            // Add Upcoming Tasks
            addUpcomingTask(e){
                e.preventDefault()

                if(this.upcoming.length > 4) {
                    alert("Please complete the upcoming task.")
                } const newTask = {
                    title: this.newTask,
                    waiting: true,
                    taskId: Math.random().toString(36).substring(7),
                };
            // Post Request
            fetch("/api/upcoming", {
                method: "POST",
                headers: {
                    "content-type": "application/json",
                },

                body: JSON.stringify(newTask),
            }).then(() => this.upcoming.push(newTask));

            // Clear or Reset new Task
            this.newTask = "";
            },

            // Delete Upcoming Task

            delUpcoming(taskId) {
                if(confirm("Are you sure?")) {
                    fetch(`/api/upcoming/${taskId}`, {
                        method: 'delete',
                    }).then((res) => res.json())
                    .then((data) => {
                        this.upcoming = this.upcoming.filter(({taskId: id}) => id !== taskId )
                    }).catch(err => console.log(err))            
                }
            },
            // Check Upcoming Task

            checkUpcoming(taskId){
                if(this.todaytasks.length > 4){
                    alert("Sorry, please complete existing task.");
                    window.location.href="/";
                } else{
                    this.addDailyTask(taskId);

                    // Deleted this task from the database
                    fetch(`/api/upcoming/${taskId}`, {method: "delete"})
                    .then(() => {
                        this.upcoming = this.upcoming.filter(({taskId: id}) => id !== taskId);
                    })
                }
            },

                //** Today Task Method**//
            fetchTodayTasks(){},
            
            addDailyTask(){
                // Get Task
                const task = this.upcoming.filter(({ taskId: id }) => id == taskId)[0];

                // Post Request
                fetch("/api/dailytask", {
                    method: "POST",
                    headers: {
                        'content-type': 'application/json'
                    },
                    body: JSON.stringify(task),
                })
                .then(() => this.todaytasks.unshift(task))
                .catch((err => console.log(err)));
            },
        }    
    };
</script>

<style lang="scss" scoped>

</style>